<?php

namespace Infinety\TemplyPages\Http\Traits;

trait JsonData
{
    /*
     * Determine if the media item has a custom property with the given name.
     */
    /**
     * @param string $dataName
     */
    public function hasCustomData(string $dataName): bool
    {
        return array_has($this->dataArray, $dataName);
    }
    /**
     * Get the value of custom property with the given name.
     *
     * @param string $dataName
     * @param mixed $default
     *
     * @return mixed
     */
    public function getCustomData(string $dataName, $default = null)
    {
        return array_get($this->dataArray, $dataName, $default);
    }
    /**
     * @param string $name
     * @param mixed $value
     *
     * @return $this
     */
    public function setCustomData(string $name, $value): self
    {
        $customData = $this->dataArray;
        array_set($customData, $name, $value);
        $this->dataArray = $customData;

        return $this;
    }
    /**
     * @param string $name
     * @return mixed
     */
    public function forgetCustomData(string $name): self
    {
        $customData = $this->dataArray;
        array_forget($customData, $name);
        $this->dataArray = $customData;

        return $this;
    }
}
