<?php
/**
 * Created by PhpStorm.
 * User: polyanin
 * Date: 06.08.2018
 * Time: 17:12
 */

namespace App\Util;


class UiDataTransmitter
{
    const COOKIE_KEY = 'aplab-admin-data';

    const CLASS_SIDEBAR_OPEN = 'aplab-admin-sidebar-open';

    const CLASS_SIDEBAR_PIN = 'aplab-admin-sidebar-pin';

    private $sidebarPin;

    private $sidebarOpen;

    public function __construct()
    {
        try {
            $data = $_COOKIE[static::COOKIE_KEY] ?? '{}';
            $data = json_decode($data);
        } catch (\Throwable $exception) {
            $data = new \stdClass;
        }
        try {
            $this->sidebarOpen = $data->sidebar_open;
        } catch (\Throwable $exception) {
            $this->sidebarOpen = false;
        }
        try {
            $this->sidebarPin = $data->sidebar_pin;
        } catch (\Throwable $exception) {
            $this->sidebarPin = false;
        }
    }

    /**
     * @return mixed
     */
    public function getSidebarPin()
    {
        return $this->sidebarPin;
    }

    /**
     * @param mixed $sidebarPin
     * @return UiDataTransmitter
     */
    public function setSidebarPin($sidebarPin)
    {
        $this->sidebarPin = (bool)$sidebarPin;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getSidebarOpen()
    {
        return $this->sidebarOpen;
    }

    /**
     * @param mixed $sidebarOpen
     * @return UiDataTransmitter
     */
    public function setSidebarOpen($sidebarOpen)
    {
        $this->sidebarOpen = (bool)$sidebarOpen;
        return $this;
    }

    public function getBodyClasses()
    {
        $tmp = [];
        if ($this->getSidebarOpen()) {
            $tmp[] = static::CLASS_SIDEBAR_OPEN;
        }
        if ($this->getSidebarPin()) {
            $tmp[] = static::CLASS_SIDEBAR_PIN;
        }
        return join(' ', $tmp);
    }
}
