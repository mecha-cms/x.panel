<?php namespace Panel;

class Lot extends \Genome {

    protected $v;

    public $assets = [];
    public $icons = [];

    public function __construct(array $value, $key = 0) {
        if (\is_string($class = $value[2]['class'] ?? 0)) {
            $class = \array_fill_keys(\preg_split('/\s+/', $class, -1, \PREG_SPLIT_NO_EMPTY), 1);
        } else {
            $class = [];
        }
        $style = []; // TODO: Parse the existing `style` attribute value to key-value pair(s)
        foreach (\step(\substr(\c2f(\trim($c = static::class, "\\")), 6), '/') as $v) {
            if ("" === $v) {
                continue;
            }
            $class[\strtr($v, '/', '-')] = 1;
        }
        if (self::class === $c) {
            $value[2]['role'] = $value[2]['role'] ?? 'application';
        }
        $has_gap = !empty($value['gap']);
        $has_height = $value['height'] ?? 0;
        $has_mark = $value['mark'] ?? false;
        $has_width = $value['width'] ?? 0;
        $is_active = !\array_key_exists('active', $value) || !empty($value['active']);
        $is_current = $value['current'] ?? false;
        $is_fix = !empty($value['fix']);
        $is_flex = !empty($value['flex']);
        $is_vital = !empty($value['vital']);
        $value[0] = $value[0] ?? 'div';
        $value[1] = $value[1] ?? "";
        if ($has_gap) {
            $class['has-gap'] = 1;
        }
        if ($has_height) {
            $class['has-height'] = 1;
            if (\is_array($has_height)) {
                $has_height = \array_replace(['0%', 0, '100%'], $has_height);
                $style['height'] = \is_int($has_height[1]) ? $has_height[1] . 'px' : $has_height[1];
                $style['max-height'] = \is_int($has_height[2]) ? $has_height[2] . 'px' : $has_height[2];
                $style['min-height'] = \is_int($has_height[0]) ? $has_height[0] . 'px' : $has_height[0];
            } else {
                $style['height'] = \is_int($has_height) ? $has_height . 'px' : $has_height;
            }
        }
        if ($has_mark) {
            $class['has-mark'] = 1;
            if (\is_string($has_mark)) {
                $class['has-mark-' . $has_mark] = 1;
            }
            $value[2]['aria-selected'] = 'true';
        }
        if ($has_width) {
            $class['has-width'] = 1;
            if (\is_array($has_width)) {
                $has_width = \array_replace(['0%', 0, '100%'], $has_width);
                $style['max-width'] = \is_int($has_width[2]) ? $has_width[2] . 'px' : $has_width[2];
                $style['min-width'] = \is_int($has_width[0]) ? $has_width[0] . 'px' : $has_width[0];
                $style['width'] = \is_int($has_width[1]) ? $has_width[1] . 'px' : $has_width[1];
            } else {
                $style['width'] = \is_int($has_width) ? $has_width . 'px' : $has_width;
            }
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
        if (\is_array($tags = $value['tags'] ?? 0)) {
            $class = \array_replace($class, \array_is_list($tags) ? \array_fill_keys($tags, 1) : $tags);
        }
        if ($class = \drop($class)) {
            \ksort($class);
            $value[2]['class'] = \implode(' ', \array_keys($class));
        }
        if ($style = \drop($style)) {
            \ksort($style);
            $value[2]['style'] = "";
            foreach ($style as $k => $v) {
                $value[2]['style'] .= $k . ': ' . (\is_int($v) ? $v . 'px' : $v) . '; ';
            }
            $value[2]['style'] = \trim($value[2]['style']);
        }
        if (\array_key_exists('content', $value)) {
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
                $type = \trim('panel/lot/' . ($v['type'] ?? (\is_string($k) ? $k : "")), '/' . "\\");
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