<?php namespace Panel;

class Y extends \Genome {

    protected $v;
    protected $value;

    public function __construct(array $value, $key = 0) {
        $class = [];
        foreach (\step(\substr(\c2f(static::class), 8), '/') as $v) {
            if ("" === $v) {
                continue;
            }
            $class['y-' . \strtr($v, '/', '-')] = 1;
        }
        $has_mark = $value['mark'] ?? false;
        $is_active = !\array_key_exists('active', $value) || !empty($value['active']);
        $is_current = $value['current'] ?? false;
        $is_fix = !empty($value['fix']);
        $is_flex = !empty($value['flex']);
        $is_vital = !empty($value['vital']);
        $value[0] = $value[0] ?? 'div';
        $value[1] = $value[1] ?? "";
        if ($has_mark) {
            $class['has-mark'] = 1;
            if (\is_string($has_mark)) {
                $class['has-mark-' . $has_mark] = 1;
            }
            $value[2]['aria-selected'] = 'true';
        }
        if ($is_active) {
            $class['is-active'] = 1;
        } else if (empty($value[2]['aria-disabled'])) {
            $class['not-active'] = 1;
            $value[2]['aria-disabled'] = 'true';
        }
        if ($is_current && empty($value[2]['aria-current'])) {
            $class['is-current'] = 1;
            if (\is_string($is_current)) {
                $class['is-current-' . $is_current] = 1;
            }
            $value[2]['aria-current'] = \is_string($is_current) ? $is_current : 'true';
        }
        if ($is_fix && empty($value[2]['aria-readonly'])) {
            $class['is-fix'] = 1;
            $value[2]['aria-readonly'] = 'true';
        }
        if ($is_flex) {
            $class['is-flex'] = 1;
            $value[2]['aria-orientation'] = $value[2]['aria-orientation'] ?? 'horizontal';
        } else {
            $value[2]['aria-orientation'] = $value[2]['aria-orientation'] ?? 'vertical';
        }
        if ($is_vital && empty($value[2]['aria-required'])) {
            $class['is-vital'] = 1;
            $value[2]['aria-required'] = 'true';
        }
        $value[2]['data-key'] = \strtr(\s($value[2]['data-key'] ?? $value['key'] ?? $key), [\PATH . \D => ".\\"]);
        $value[2]['data-stack'] = $value[2]['data-stack'] ?? $value['stack'] ?? null;
        if (!empty($value['description']) && empty($value[2]['aria-description'])) {
            $class['has-description'] = 1;
            $value[2]['aria-description'] = \i(...((array) $value['description']));
        }
        if (!empty($value['hint']) && empty($value[2]['aria-placeholder'])) {
            $class['has-hint'] = 1;
            $value[2]['aria-placeholder'] = \i(...((array) $value['hint']));
        }
        if (!empty($value['id']) && \is_scalar($value['id']) && empty($value[2]['id'])) {
            $value[2]['id'] = \s($value['id']);
        }
        if (!empty($value['level']) && \is_int($value['level']) && empty($value[2]['aria-level'])) {
            $value[2]['aria-level'] = $value['level'];
        }
        if (!empty($value['title']) && empty($value[2]['aria-label'])) {
            $class['has-title'] = 1;
            $value[2]['aria-label'] = \i(...((array) $value['title']));
        }
        foreach (['chunk', 'count', 'deep', 'part', 'sort'] as $k) {
            if (!empty($value[$k]) && empty($value[2]['data-' . $k])) {
                $value[2]['data-' . $k] = \json_encode($value[$k]);
            }
        }
        foreach (['are', 'as', 'can', 'has', 'is', 'not', 'of', 'with'] as $k) {
            if (!\array_key_exists($k, $value) || !\is_array($value[$k])) {
                continue;
            }
            foreach ($value[$k] as $kk => $vv) {
                $class[$k . '-' . $kk] = $vv;
            }
        }
        if ($class = \array_filter($class)) {
            \ksort($class);
            $value[2]['class'] = \implode(' ', \array_keys($class));
        }
        $this->value = $value;
        if (\array_key_exists('content', $value)) {
            $value[2]['data-count-content'] = $value[2]['data-count-content'] ?? 1;
            $value[1] = \s($value['content']);
        } else if (isset($value['lot']) && \is_array($value['lot'])) {
            $n = ($lot = (new \Anemone($value['lot']))->sort([1, 'stack', 10], true))->count();
            $has_part = !empty($value['chunk']) && \is_int($value['chunk']) && $value['chunk'] < $n;
            $value[1] = [];
            $value[2]['data-count-lot'] = $value[2]['data-count-lot'] ?? $n;
            $value[2]['role'] = $value[2]['role'] ?? 'group';
            foreach ($lot as $k => $v) {
                if (false === $v || null === $v || !empty($v['skip'])) {
                    continue;
                }
                $index = 0;
                $type = \trim('panel/y/' . ($v['type'] ?? ""), '/' . "\\");
                foreach (\step(\f2c($type), "\\") as $c) {
                    try {
                        $index += 1;
                        $c = (new \ReflectionClass($c))->newInstance($v, $k);
                        if ($has_part) {
                            $c->v['aria-posinset'] = $c->v['aria-posinset'] ?? $index;
                            $c->v['aria-setsize'] = $c->v['aria-setsize'] ?? $n;
                        }
                        $value[1][$k] = $c->v;
                        break;
                    } catch (\Throwable $e) {}
                }
            }
        }
        $this->v = new \HTML($value, true);
    }

    public function __toString(): string {
        return (string) $this->v;
    }

}