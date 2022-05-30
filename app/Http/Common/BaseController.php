<?php

namespace App\Http\Common;

use App\Foundation\HTTP\Response;
use App\Models\Common\BaseModel;

abstract class BaseController
{
    /**
     * Controller base model.
     *
     * @var BaseModel
     */
    protected BaseModel $current_model;

    /**
     * Array of options. Meta information for controller actions.
     *
     * @var array
     */
    protected array $options = [];

    /**
     * @return BaseModel
     */
    public function getCurrentModel(): BaseModel
    {
        return $this->current_model;
    }

    /**
     * @param  BaseModel  $current_model
     */
    public function setCurrentModel(BaseModel $current_model): void
    {
        $this->current_model = $current_model;
    }

    /**
     * @return array
     */
    public function getOptions(): array
    {
        return $this->options;
    }

    /**
     * @param  array  $options
     */
    public function setOptions(array $options): void
    {
        $this->options = $options;
    }

    /**
     * Set option.
     *
     * @param string $key
     * @param mixed $value
     * @return array
     */
    protected function setOption(string $key, mixed $value): array
    {
        return helper_array_set($this->options, $key, $value);
    }

    /**
     * Get option.
     *
     * @param  string  $key
     * @return mixed
     */
    protected function getOption(string $key): mixed
    {
        $result = helper_array_get($this->options, $key);

        return $result == null ? false : $result;
    }

    protected function initFunction($function_meta = []): void
    {
        if (isset($function_meta['options']))
            $this->setOptions($function_meta['options']);
    }

    public function respond(mixed $data, int $code = 200, array $headers = []): Response
    {
        if ($data instanceof BaseModel)
        {
            $data = $data->toArray();
        }

        $response = new Response([
            'data' => $data,
        ], $code, $headers);
        return $response;
    }
}
