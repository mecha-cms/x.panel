<?php namespace Panel;

class Type extends \Genome {

    protected $v;

    public $type = 'text/html';

    public function __construct(array $value, $key = 0) {
        $lot = new \Panel\Lot($value);
        $type = \substr(\c2f(\trim(static::class, "\\")), 11);
        $this->v = new \HTML([
            0 => 'html',
            1 => [
                0 => ['head', [
                    0 => ['meta', false, [
                        'content' => 'width=device-width',
                        'name' => 'viewport'
                    ]],
                    1 => ['meta', false, ['charset' => 'utf-8']],
                    2 => ['title', \lot('t'), []],
                    3 => ['link', false, [
                        'href' => '/favicon.ico',
                        'rel' => 'icon'
                    ]]
                ]],
                1 => ['body', [
                    0 => "",
                    1 => (string) $lot,
                    2 => ""
                ], ['spellcheck' => 'false']]
            ],
            2 => [
                'class' => true,
                'dir' => 'ltr',
                'data-type' => "" !== $type ? $type : 'blank'
            ]
        ], true);
    }

    public function __toString(): string {
        return '<!DOCTYPE html>' . $this->v;
    }

}