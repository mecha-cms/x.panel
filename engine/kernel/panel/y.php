<?php namespace Panel;

class Y extends \Genome {

    protected $v;
    protected $value;

    public function __construct(array $value, $key = 0) {
        $value[0] = $value[0] ?? 'div';
        $value[1] = $value[1] ?? "";
        $value[2]['data-key'] = \s($value[2]['data-key'] ?? $value['key'] ?? $key);
        $value[2]['data-stack'] = $value[2]['data-stack'] ?? $value['stack'] ?? null;
        $class = [];
        foreach (\step(\substr(\c2f(static::class), 8), '/') as $v) {
            if ("" === $v) {
                continue;
            }
            $class[] = \strtr($v, '/', '-');
        }
        if ($class) {
            $value[2]['class'] = \implode(' ', $class);
        }
        $this->value = $value;
        if (\array_key_exists('content', $value)) {
            $value[1] = \s($value['content']);
        } else if (isset($value['lot']) && \is_array($value['lot'])) {
            $n = ($lot = (new \Anemone($value['lot']))->sort([1, 'stack', 10], true))->count();
            $value[1] = [];
            $value[2]['aria-orientation'] = $value[2]['aria-orientation'] ?? 'vertical';
            $value[2]['data-stack-count'] = $n;
            $value[2]['role'] = $value[2]['role'] ?? 'group';
            foreach ($lot as $k => $v) {
                $index = 0;
                $type = \trim('panel/y/' . ($v['type'] ?? ""), '/' . "\\");
                foreach (\step(\f2c($type), "\\") as $c) {
                    try {
                        $index += 1;
                        $c = (new \ReflectionClass($c))->newInstance($v, $k);
                        $c->v['aria-posinset'] = $c->v['aria-posinset'] ?? $index;
                        $c->v['aria-setsize'] = $c->v['aria-setsize'] ?? $n;
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