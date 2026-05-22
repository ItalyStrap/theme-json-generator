<?php

declare(strict_types=1);

namespace ItalyStrap\Tests;

trait CssParserScenarioProviderTrait
{
    public static function cssParserScenarioProvider(): iterable
    {
        // phpcs:disable Generic.Files.LineLength.TooLong
        yield 'root rule' => [
            'selector' => '.test-selector',
            'actual' => '.test-selector{height: 100%;}',
            'expectedParsedCss' => 'height: 100%;',
            'expectedWordPressCss' => ':root :where(.test-selector){height: 100%;}',
        ];

        yield 'root rule with multiple declarations' => [
            'selector' => '.test-selector',
            'actual' => '.test-selector{height: 100%;width: 100%;color: red;}',
            'expectedParsedCss' => 'height: 100%;width: 100%;color: red;',
            'expectedWordPressCss' => ':root :where(.test-selector){height: 100%;width: 100%;color: red;}',
        ];

        yield 'root rule with pseudo class' => [
            'selector' => '.test-selector',
            'actual' => '.test-selector{height: 100%;width: 100%;color: red;}.test-selector:hover {color: red;}',
            'expectedParsedCss' => 'height: 100%;width: 100%;color: red;&:hover{color: red;}',
            'expectedWordPressCss' => ':root :where(.test-selector){height: 100%;width: 100%;color: red;}:root :where(.test-selector:hover){color: red;}',
        ];
        yield 'root rule with pseudo class and pseudo element' => [
            'selector' => '.test-selector',
            'actual' => '.test-selector{height: 100%;}.test-selector:hover{color: red;}.test-selector::placeholder{color: blue;}',
            'expectedParsedCss' => 'height: 100%;&:hover{color: red;}&::placeholder{color: blue;}',
            'expectedWordPressCss' => ':root :where(.test-selector){height: 100%;}:root :where(.test-selector:hover){color: red;}:root :where(.test-selector)::placeholder{color: blue;}',
        ];

        yield 'root rule with multiple declarations, pseudo class and pseudo element' => [
            'selector' => '.test-selector',
            'actual' => '.test-selector{height: 100%;width: 100%;color: red;}.test-selector:hover {color: red;}.test-selector::placeholder {color: red;}',
            'expectedParsedCss' => 'height: 100%;width: 100%;color: red;&:hover{color: red;}&::placeholder{color: red;}',
            'expectedWordPressCss' => ':root :where(.test-selector){height: 100%;width: 100%;color: red;}:root :where(.test-selector:hover){color: red;}:root :where(.test-selector)::placeholder{color: red;}',
        ];


        yield 'pseudo class only' => [
            'selector' => '.test-selector',
            'actual' => '.test-selector:hover {color: red;}',
            'expectedParsedCss' => ':hover{color: red;}',
            'expectedWordPressCss' => ':root :where(.test-selector:hover){color: red;}',
        ];

        yield 'pseudo class only with multiple declarations' => [
            'selector' => '.test-selector',
            'actual' => '.test-selector:hover {color: red;height: 100%;}',
            'expectedParsedCss' => ':hover{color: red;height: 100%;}',
            'expectedWordPressCss' => ':root :where(.test-selector:hover){color: red;height: 100%;}',
        ];
        yield 'pseudo class and pseudo element only' => [
            'selector' => '.test-selector',
            'actual' => '.test-selector:hover {color: red;height: 100%;}.test-selector::placeholder {color: red;}',
            'expectedParsedCss' => ':hover{color: red;height: 100%;}&::placeholder{color: red;}',
            'expectedWordPressCss' => ':root :where(.test-selector:hover){color: red;height: 100%;}:root :where(.test-selector)::placeholder{color: red;}',
        ];


        yield 'pseudo element only' => [
            'selector' => '.test-selector',
            'actual' => '.test-selector::placeholder {color: red;}',
            'expectedParsedCss' => '::placeholder{color: red;}',
            'expectedWordPressCss' => ':root :where(.test-selector)::placeholder{color: red;}',
        ];

        yield 'pseudo element only with multiple declarations' => [
            'selector' => '.test-selector',
            'actual' => '.test-selector::placeholder {color: red;height: 100%;}',
            'expectedParsedCss' => '::placeholder{color: red;height: 100%;}',
            'expectedWordPressCss' => ':root :where(.test-selector)::placeholder{color: red;height: 100%;}',
        ];
        yield 'root rule with pseudo element' => [
            'selector' => '.test-selector',
            'actual' => '.test-selector{height: 100%;}.test-selector::placeholder {color: red;}',
            'expectedParsedCss' => 'height: 100%;&::placeholder{color: red;}',
            'expectedWordPressCss' => ':root :where(.test-selector){height: 100%;}:root :where(.test-selector)::placeholder{color: red;}',
        ];

        yield 'root and descendant selector' => [
            'selector' => '.test-selector',
            'actual' => '.test-selector{height: 100%;}.test-selector .foo{color: red;}',
            'expectedParsedCss' => 'height: 100%;& .foo{color: red;}',
            'expectedWordPressCss' => ':root :where(.test-selector){height: 100%;}:root :where(.test-selector .foo){color: red;}',
        ];

        yield 'root and descendant selector with multiple declarations' => [
            'selector' => '.test-selector',
            'actual' => '.test-selector{height: 100%;width: 100%;}.test-selector .foo{color: red;height: 100%;}',
            'expectedParsedCss' => 'height: 100%;width: 100%;& .foo{color: red;height: 100%;}',
            'expectedWordPressCss' => ':root :where(.test-selector){height: 100%;width: 100%;}:root :where(.test-selector .foo){color: red;height: 100%;}',
        ];

        yield 'descendant selector only' => [
            'selector' => '.test-selector',
            'actual' => '.test-selector .foo{height: 100%;left: 0;position: absolute;top: 0;width: 100%;}',
            'expectedParsedCss' => ' .foo{height: 100%;left: 0;position: absolute;top: 0;width: 100%;}',
            'expectedWordPressCss' => ':root :where(.test-selector .foo){height: 100%;left: 0;position: absolute;top: 0;width: 100%;}',
        ];

        yield 'multiple descendant selectors' => [
            'selector' => '.test-selector',
            'actual' => '.test-selector .foo{height: 100%;left: 0;position: absolute;top: 0;width: 100%;color: red;}.test-selector .foo .bar{height: 100%;left: 0;position: absolute;top: 0;width: 100%;color: red;}',
            'expectedParsedCss' => ' .foo{height: 100%;left: 0;position: absolute;top: 0;width: 100%;color: red;}& .foo .bar{height: 100%;left: 0;position: absolute;top: 0;width: 100%;color: red;}',
            'expectedWordPressCss' => ':root :where(.test-selector .foo){height: 100%;left: 0;position: absolute;top: 0;width: 100%;color: red;}:root :where(.test-selector .foo .bar){height: 100%;left: 0;position: absolute;top: 0;width: 100%;color: red;}',
        ];

        yield 'descendant selectors with pseudo class' => [
            'selector' => '.test-selector',
            'actual' => '.test-selector .foo{height: 100%;left: 0;position: absolute;top: 0;width: 100%;color: red;}.test-selector .foo .bar{height: 100%;left: 0;position: absolute;top: 0;width: 100%;color: red;}.test-selector table{border-collapse: collapse;border-spacing: 0;}.test-selector:hover {height: 100%;left: 0;position: absolute;top: 0;width: 100%;color: red;}',
            'expectedParsedCss' => ' .foo{height: 100%;left: 0;position: absolute;top: 0;width: 100%;color: red;}& .foo .bar{height: 100%;left: 0;position: absolute;top: 0;width: 100%;color: red;}& table{border-collapse: collapse;border-spacing: 0;}&:hover{height: 100%;left: 0;position: absolute;top: 0;width: 100%;color: red;}',
            'expectedWordPressCss' => ':root :where(.test-selector .foo){height: 100%;left: 0;position: absolute;top: 0;width: 100%;color: red;}:root :where(.test-selector .foo .bar){height: 100%;left: 0;position: absolute;top: 0;width: 100%;color: red;}:root :where(.test-selector table){border-collapse: collapse;border-spacing: 0;}:root :where(.test-selector:hover){height: 100%;left: 0;position: absolute;top: 0;width: 100%;color: red;}',
        ];

        yield 'descendant selectors with pseudo class and pseudo element' => [
            'selector' => '.test-selector',
            'actual' => '.test-selector .foo{height: 100%;left: 0;position: absolute;top: 0;width: 100%;color: red;}.test-selector .foo .bar{height: 100%;left: 0;position: absolute;top: 0;width: 100%;color: red;}.test-selector table{border-collapse: collapse;border-spacing: 0;}.test-selector:hover{height: 100%;left: 0;position: absolute;top: 0;width: 100%;color: red;}.test-selector::placeholder{height: 100%;left: 0;position: absolute;top: 0;width: 100%;color: red;}',
            'expectedParsedCss' => ' .foo{height: 100%;left: 0;position: absolute;top: 0;width: 100%;color: red;}& .foo .bar{height: 100%;left: 0;position: absolute;top: 0;width: 100%;color: red;}& table{border-collapse: collapse;border-spacing: 0;}&:hover{height: 100%;left: 0;position: absolute;top: 0;width: 100%;color: red;}&::placeholder{height: 100%;left: 0;position: absolute;top: 0;width: 100%;color: red;}',
            'expectedWordPressCss' => ':root :where(.test-selector .foo){height: 100%;left: 0;position: absolute;top: 0;width: 100%;color: red;}:root :where(.test-selector .foo .bar){height: 100%;left: 0;position: absolute;top: 0;width: 100%;color: red;}:root :where(.test-selector table){border-collapse: collapse;border-spacing: 0;}:root :where(.test-selector:hover){height: 100%;left: 0;position: absolute;top: 0;width: 100%;color: red;}:root :where(.test-selector)::placeholder{height: 100%;left: 0;position: absolute;top: 0;width: 100%;color: red;}',
        ];


        yield 'root custom properties' => [
            'selector' => '.test-selector',
            'actual' => '.test-selector{--foo: 100%;}',
            'expectedParsedCss' => '--foo: 100%;',
            'expectedWordPressCss' => ':root :where(.test-selector){--foo: 100%;}',
        ];

        yield 'root custom properties with multiple declarations' => [
            'selector' => '.test-selector',
            'actual' => '.test-selector{--foo: 100%;--bar: 100%;}',
            'expectedParsedCss' => '--foo: 100%;--bar: 100%;',
            'expectedWordPressCss' => ':root :where(.test-selector){--foo: 100%;--bar: 100%;}',
        ];
        yield 'nested appended and descendant selectors' => [
            'selector' => '.test-selector',
            'actual' => '.test-selector{color: red; margin: auto;}.test-selector.one{color: blue;}.test-selector .two{color: green;}',
            'expectedParsedCss' => 'color: red;margin: auto;&.one{color: blue;}& .two{color: green;}',
            'expectedWordPressCss' => ':root :where(.test-selector){color: red;margin: auto;}:root :where(.test-selector.one){color: blue;}:root :where(.test-selector .two){color: green;}',
        ];


        yield 'root rule with !important' => [
            'selector' => '.test-selector',
            'actual' => '.test-selector{color: red !important;}',
            'expectedParsedCss' => 'color: red !important;',
            'expectedWordPressCss' => ':root :where(.test-selector){color: red !important;}',
        ];
        yield 'nested selector with !important' => [
            'selector' => '.test-selector',
            'actual' => '.test-selector{color: red; margin: auto;}.test-selector.one{color: blue;}.test-selector .two{color: green !important;}',
            'expectedParsedCss' => 'color: red;margin: auto;&.one{color: blue;}& .two{color: green !important;}',
            'expectedWordPressCss' => ':root :where(.test-selector){color: red;margin: auto;}:root :where(.test-selector.one){color: blue;}:root :where(.test-selector .two){color: green !important;}',
        ];

        yield 'descendant and appended selectors' => [
            'selector' => '.test-selector',
            'actual' => '.test-selector .foo{color: red;}.test-selector.is-active{color: blue;}',
            'expectedParsedCss' => ' .foo{color: red;}&.is-active{color: blue;}',
            'expectedWordPressCss' => ':root :where(.test-selector .foo){color: red;}:root :where(.test-selector.is-active){color: blue;}',
        ];

        yield 'selector used also as prefix for nested selectors' => [
            'selector' => '.test-selector',
            'actual' => '.test-selector .test-selector-one{color: blue;}.test-selector .test-selector-two{color: red;}',
            'expectedParsedCss' => ' .test-selector-one{color: blue;}& .test-selector-two{color: red;}',
            'expectedWordPressCss' => ':root :where(.test-selector .test-selector-one){color: blue;}:root :where(.test-selector .test-selector-two){color: red;}',
        ];

        yield 'selector list' => [
            'selector' => '.test-selector',
            'actual' => '.test-selector .one,.test-selector .two,.test-selector.three,.test-selector #four{color: red;}',
            'expectedParsedCss' => ' .one{color: red;}& .two{color: red;}&.three{color: red;}& #four{color: red;}',
            'expectedWordPressCss' => ':root :where(.test-selector .one){color: red;}:root :where(.test-selector .two){color: red;}:root :where(.test-selector.three){color: red;}:root :where(.test-selector #four){color: red;}',
        ];

        yield 'root custom properties mixed with nested selectors' => [
            'selector' => '.test-selector',
            'actual' => '.test-selector{--foo: 100%;--bar: 100%;}.test-selector #firstParagraph{background-color: var(--first-color);color: var(--second-color);}.test-selector .foo{--bar: 50%;color: red;width: var(--foo);height: var(--bar);}',
            'expectedParsedCss' => '--foo: 100%;--bar: 100%;& #firstParagraph{background-color: var(--first-color);color: var(--second-color);}& .foo{--bar: 50%;color: red;width: var(--foo);height: var(--bar);}',
            'expectedWordPressCss' => ':root :where(.test-selector){--foo: 100%;--bar: 100%;}:root :where(.test-selector #firstParagraph){background-color: var(--first-color);color: var(--second-color);}:root :where(.test-selector .foo){--bar: 50%;color: red;width: var(--foo);height: var(--bar);}',
        ];

        yield 'root custom properties mixed with nested selectors before root' => [
            'selector' => '.test-selector',
            'actual' => '.test-selector #firstParagraph{background-color: var(--first-color);color: var(--second-color);}.test-selector{--foo: 100%;--bar: 100%;}.test-selector .foo {--bar: 50%;color: red;width: var(--foo);height: var(--bar);}',
            'expectedParsedCss' => '--foo: 100%;--bar: 100%;& #firstParagraph{background-color: var(--first-color);color: var(--second-color);}& .foo{--bar: 50%;color: red;width: var(--foo);height: var(--bar);}',
            'expectedWordPressCss' => ':root :where(.test-selector){--foo: 100%;--bar: 100%;}:root :where(.test-selector #firstParagraph){background-color: var(--first-color);color: var(--second-color);}:root :where(.test-selector .foo){--bar: 50%;color: red;width: var(--foo);height: var(--bar);}',
        ];


        yield 'multiline root and nested selectors' => [
            'selector' => '.test-selector',
            'actual' => <<<'CUSTOM_CSS'
.test-selector .bar{
    color: red;
}
.test-selector {
    --foo: 100%;
    height: 100%;
    width: 100%;
    color: red;
}
.test-selector .foo{
    --bar: 50%;
    color: red;
    width: var(--foo);
    height: var(--bar);
}
CUSTOM_CSS,
            'expectedParsedCss' => '--foo: 100%;height: 100%;width: 100%;color: red;& .bar{color: red;}& .foo{--bar: 50%;color: red;width: var(--foo);height: var(--bar);}',
            'expectedWordPressCss' => ':root :where(.test-selector){--foo: 100%;height: 100%;width: 100%;color: red;}:root :where(.test-selector .bar){color: red;}:root :where(.test-selector .foo){--bar: 50%;color: red;width: var(--foo);height: var(--bar);}',
        ];

        yield 'descendant selector list with whitespace' => [
            'selector' => '.test-selector',
            'actual' => '.test-selector .one ,.test-selector .two,.test-selector .three{color: red;}',
            'expectedParsedCss' => ' .one{color: red;}& .two{color: red;}& .three{color: red;}',
            'expectedWordPressCss' => ':root :where(.test-selector .one){color: red;}:root :where(.test-selector .two){color: red;}:root :where(.test-selector .three){color: red;}',
        ];
        // phpcs:enable Generic.Files.LineLength.TooLong
    }
}
