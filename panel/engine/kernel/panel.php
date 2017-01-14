<?php

class Panel extends Genome {

    public static function set($key, $value = null) {
        $id = '__' . static::class . '.';
        if (!__is_anemon__($key)) {
            return Config::set($id . $key, $value);
        }
        foreach ($key as $k => $v) {
            $keys[$id . $k] = $v;
        }
        return Config::set(isset($keys) ? $keys : [], $value);
    }

    public static function get($key = null, $fail = false) {
        $id = '__' . static::class;
        if (!isset($key)) {
            return Config::get($id, $fail);
        }
        return Config::get($id . '.' . $key, $fail);
    }

    public static function __callStatic($kin, $lot) {
        return call_user_func_array([new static, $kin], $lot);
    }

    public function __call($key, $lot) {
        return Config::get('__' . static::class . '.' . $key, array_shift($lot));
    }

    public function __set($key, $value = null) {
        return Config::set('__' . static::class . '.' . $key, $value);
    }

    public function __get($key) {
        return Config::get('__' . static::class . '.' . $key, null);
    }

    public function __unset($key) {
        return Config::reset('__' . static::class . '.' . $key);
    }

    public function __toString() {
        return To::yaml(Config::get('__' . static::class));
    }

    public function __invoke($fail = []) {
        return Config::get('__' . static::class, o($fail));
    }

}