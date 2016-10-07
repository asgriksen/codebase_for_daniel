<?php

namespace App\Support\Model\Traits;

trait ApplyActionTrait
{
    /**
     * Pause the item.
     *
     * @return bool
     */
    public function Pause()
    {
        // Connect to facebook and access the data transfer object.
        $dto = $this->getDataTransferObject();

        // Pause it.
        return $dto->pause();
    }

    /**
     * Run the item.
     *
     * @return bool
     */
    public function Run()
    {
        // Connect to facebook and access the data transfer object.
        $dto = $this->getDataTransferObject();

        // Run it.
        return $dto->run();
    }
}