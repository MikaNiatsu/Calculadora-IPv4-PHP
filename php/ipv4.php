<?php

class IPv4Calculator
{
    private $ip;
    private $mascara;
    private $cidr;
    private $binario;
    private $clase;
    private $red;
    private $broadcast;
    private $primerHost;
    private $ultimoHost;
    private $numeroHosts;
    private $binarioRed;
    private $binarioHost;
    private $binarioSubred;

    public function __construct($ip, $mascara)
    {
        if (!$this->validarIP($ip)) {
            throw new Exception("Dirección IP inválida");
        }
        $this->ip = $ip;

        if (strpos($mascara, '.') !== false) {
            if (!$this->validarMascara($mascara)) {
                throw new Exception("Máscara de subred inválida");
            }
            $this->mascara = $mascara;
            $this->cidr = $this->mascaraACIDR($mascara);
        } else {
            if (!$this->validarCIDR($mascara)) {
                throw new Exception("Valor CIDR inválido");
            }
            $this->cidr = intval($mascara);
            $this->mascara = $this->CIDRaMascara($this->cidr);
        }

        $this->calcularTodo();
    }

    private function validarIP($ip)
    {
        return filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4);
    }

    private function validarMascara($mascara)
    {
        if (!$this->validarIP($mascara)) {
            return false;
        }
        $binario = implode('', array_map(function ($octet) {
            return str_pad(decbin($octet), 8, '0', STR_PAD_LEFT);
        }, explode('.', $mascara)));
        return preg_match('/^1+0+$/', $binario);
    }

    private function validarCIDR($cidr)
    {
        return filter_var($cidr, FILTER_VALIDATE_INT, array("options" => array("min_range" => 0, "max_range" => 32))) !== false;
    }

    private function mascaraACIDR($mascara)
    {
        return substr_count(implode('', array_map(function ($octet) {
            return str_pad(decbin($octet), 8, '0', STR_PAD_LEFT);
        }, explode('.', $mascara))), '1');
    }

    private function CIDRaMascara($cidr)
    {
        return long2ip(~(pow(2, 32 - $cidr) - 1));
    }

    private function calcularTodo()
    {
        $this->convertirABinario();
        $this->determinarClase();
        $this->calcularRed();
        $this->calcularBroadcast();
        $this->calcularRangoHosts();
        $this->calcularNumeroHosts();
        $this->separarPartesBinarias();
    }

    private function convertirABinario()
    {
        $octetos = explode('.', $this->ip);
        $binario = [];
        foreach ($octetos as $octeto) {
            $binario[] = str_pad(decbin($octeto), 8, '0', STR_PAD_LEFT);
        }
        $this->binario = implode('.', $binario);
    }

    private function determinarClase()
    {
        $primerOcteto = intval(explode('.', $this->ip)[0]);
        if ($primerOcteto >= 1 && $primerOcteto <= 126) {
            $this->clase = 'A';
        } elseif ($primerOcteto >= 128 && $primerOcteto <= 191) {
            $this->clase = 'B';
        } elseif ($primerOcteto >= 192 && $primerOcteto <= 223) {
            $this->clase = 'C';
        } elseif ($primerOcteto >= 224 && $primerOcteto <= 239) {
            $this->clase = 'D (Multicast)';
        } elseif ($primerOcteto >= 240 && $primerOcteto <= 255) {
            $this->clase = 'E (Reservada)';
        } else {
            $this->clase = 'Desconocida';
        }
    }

    private function calcularRed()
    {
        $ipLong = ip2long($this->ip);
        $mascaraLong = ip2long($this->mascara);
        $this->red = long2ip($ipLong & $mascaraLong);
    }

    private function calcularBroadcast()
    {
        $ipLong = ip2long($this->ip);
        $mascaraLong = ip2long($this->mascara);
        $this->broadcast = long2ip($ipLong | (~$mascaraLong));
    }

    private function calcularRangoHosts()
    {
        $redLong = ip2long($this->red);
        $broadcastLong = ip2long($this->broadcast);
        $this->primerHost = long2ip($redLong + 1);
        $this->ultimoHost = long2ip($broadcastLong - 1);
    }

    private function calcularNumeroHosts()
    {
        $this->numeroHosts = pow(2, 32 - $this->cidr) - 2;
    }

    private function separarPartesBinarias()
    {
        $binarioSinPuntos = str_replace('', '', $this->binario);
        $claseSubred = ['A' => 8, 'B' => 16, 'C' => 24];
        $bitsPorDefectoRed = isset($claseSubred[$this->clase]) ? $claseSubred[$this->clase] : 0;
        $this->binarioRed = substr($binarioSinPuntos, 0, $this->cidr);
        $this->binarioHost = substr($binarioSinPuntos, $this->cidr);
        $this->binarioSubred = substr($binarioSinPuntos, $bitsPorDefectoRed, $this->cidr - $bitsPorDefectoRed);
    }

    public function esPrivada()
    {
        $octetos = explode('.', $this->ip);
        if ($octetos[0] == 10) return true;
        if ($octetos[0] == 172 && $octetos[1] >= 16 && $octetos[1] <= 31) return true;
        if ($octetos[0] == 192 && $octetos[1] == 168) return true;
        return false;
    }

    public function esLoopback()
    {
        return substr($this->ip, 0, 4) === '127.';
    }

    public function getInfo()
    {
        return [
            'IP' => $this->ip,
            'Máscara de red' => $this->mascara,
            'CIDR' => "/{$this->cidr}",
            'Binario' => '<div style="display: flex; align-items: center; position: relative;">
                <span style="background-color: #ff6b6b; color: white; padding: 4px 8px; border-radius: 4px; position: relative; z-index: 3;">' . $this->binarioRed . '</span>
                <span style="background-color: #4ecdc4; color: white; padding: 4px 8px; border-radius: 4px; position: relative; z-index: 2; margin-left: -8px;">' . $this->binarioSubred . '</span>
                <span style="background-color: #45b7d1; color: white; padding: 4px 8px; border-radius: 4px; position: relative; z-index: 1; margin-left: -8px;">' . $this->binarioHost . '</span>
                <div style="display: flex; flex-direction: column; margin-left: 10px;">
                    <span style="color: #ff6b6b; font-size: 0.8em;">red</span>
                    <span style="color: #4ecdc4; font-size: 0.8em;">subred</span>
                    <span style="color: #45b7d1; font-size: 0.8em;">host</span>
                </div>
              </div>',
            'Clase' => $this->clase,
            'Dirección de red' => $this->red,
            'Dirección de broadcast' => $this->broadcast,
            'Primer host utilizable' => $this->primerHost,
            'Último host utilizable' => $this->ultimoHost,
            'Rango de IP' => $this->primerHost . ' - ' . $this->ultimoHost,
            'Número de hosts utilizables' => $this->numeroHosts,
            'Es privada' => $this->esPrivada() ? 'Si' : 'No',
            'Es loopback (localhost)' => $this->esLoopback() ? 'Si' : 'No',
        ];
    }
}
