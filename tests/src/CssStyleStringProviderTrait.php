<?php

declare(strict_types=1);

namespace ItalyStrap\Tests;

trait CssStyleStringProviderTrait
{
    public static function styleProvider(): iterable
    {
        yield 'root element' => [
            'selector' => '.test-selector',
            'actual' => '.test-selector{height: 100%;}',
            'expected' => 'height: 100%;',
        ];

        yield 'root element with multiple rules' => [
            'selector' => '.test-selector',
            'actual' => '.test-selector{height: 100%;width: 100%;color: red;}',
            'expected' => 'height: 100%;width: 100%;color: red;',
        ];

        yield 'root element with multiple rules and pseudo class' => [
            'selector' => '.test-selector',
            'actual' => '.test-selector{height: 100%;width: 100%;color: red;}.test-selector:hover {color: red;}',
            'expected' => 'height: 100%;width: 100%;color: red;&:hover {color: red;}',
        ];

        yield 'root element with multiple rules and pseudo class and pseudo element' => [
            // phpcs:disable
            'selector' => '.test-selector',
            'actual' => '.test-selector{height: 100%;width: 100%;color: red;}.test-selector:hover {color: red;}.test-selector::placeholder {color: red;}',
            'expected' => 'height: 100%;width: 100%;color: red;&:hover {color: red;}&::placeholder {color: red;}',
            // phpcs:enable
        ];

        yield 'pseudo single class' => [
            'selector' => '.test-selector',
            'actual' => '.test-selector:hover {color: red;}',
            'expected' => ':hover {color: red;}',
        ];

        yield 'pseudo single class with multiple rules' => [
            'selector' => '.test-selector',
            'actual' => '.test-selector:hover {color: red;height: 100%;}',
            'expected' => ':hover {color: red;height: 100%;}',
        ];

        yield 'pseudo single class with multiple rules and pseudo element' => [
            'selector' => '.test-selector',
            'actual' => '.test-selector:hover {color: red;height: 100%;}.test-selector::placeholder {color: red;}',
            'expected' => ':hover {color: red;height: 100%;}&::placeholder {color: red;}',
        ];

        yield 'simple pseudo element ::placeholder ' => [
            'selector' => '.test-selector',
            'actual' => '.test-selector::placeholder {color: red;}',
            'expected' => '::placeholder {color: red;}',
        ];

        yield 'simple pseudo element with multiple rules ::placeholder ' => [
            'selector' => '.test-selector',
            'actual' => '.test-selector::placeholder {color: red;height: 100%;}',
            'expected' => '::placeholder {color: red;height: 100%;}',
        ];

        yield 'root element with pseudo element' => [
            'selector' => '.test-selector',
            'actual' => '.test-selector{height: 100%;}.test-selector::placeholder {color: red;}',
            'expected' => 'height: 100%;&::placeholder {color: red;}',
        ];

        yield 'mixed css example' => [
            'selector' => '.test-selector',
            'actual' => '.test-selector{height: 100%;}.test-selector .foo{color: red;}',
            'expected' => 'height: 100%;& .foo{color: red;}',
        ];

        yield 'mixed css example with multiple rules' => [
            'selector' => '.test-selector',
            'actual' => '.test-selector{height: 100%;width: 100%;}.test-selector .foo{color: red;height: 100%;}',
            'expected' => 'height: 100%;width: 100%;& .foo{color: red;height: 100%;}',
        ];

        yield 'simple css example' => [
            'selector' => '.test-selector',
            'actual' => '.test-selector .foo{height: 100%;left: 0;position: absolute;top: 0;width: 100%;}',
            'expected' => ' .foo{height: 100%;left: 0;position: absolute;top: 0;width: 100%;}',
        ];

        yield 'simple css example with multiple rules' => [
            // phpcs:disable
            'selector' => '.test-selector',
            'actual' => '.test-selector .foo{height: 100%;left: 0;position: absolute;top: 0;width: 100%;color: red;}.test-selector .foo .bar{height: 100%;left: 0;position: absolute;top: 0;width: 100%;color: red;}',
            'expected' => ' .foo{height: 100%;left: 0;position: absolute;top: 0;width: 100%;color: red;}& .foo .bar{height: 100%;left: 0;position: absolute;top: 0;width: 100%;color: red;}',
            // phpcs:enable
        ];

        yield 'simple css example with multiple rules and pseudo class' => [
            // phpcs:disable
            'selector' => '.test-selector',
            'actual' => '.test-selector .foo{height: 100%;left: 0;position: absolute;top: 0;width: 100%;color: red;}.test-selector .foo .bar{height: 100%;left: 0;position: absolute;top: 0;width: 100%;color: red;}.test-selector table{border-collapse: collapse;border-spacing: 0;}.test-selector:hover {height: 100%;left: 0;position: absolute;top: 0;width: 100%;color: red;}',
            'expected' => ' .foo{height: 100%;left: 0;position: absolute;top: 0;width: 100%;color: red;}& .foo .bar{height: 100%;left: 0;position: absolute;top: 0;width: 100%;color: red;}& table{border-collapse: collapse;border-spacing: 0;}&:hover {height: 100%;left: 0;position: absolute;top: 0;width: 100%;color: red;}',
            // phpcs:enable
        ];

        yield 'simple css example with multiple rules and pseudo class and pseudo element' => [
            // phpcs:disable
            'selector' => '.test-selector',
            'actual' => '.test-selector .foo{height: 100%;left: 0;position: absolute;top: 0;width: 100%;color: red;}.test-selector .foo .bar{height: 100%;left: 0;position: absolute;top: 0;width: 100%;color: red;}.test-selector table{border-collapse: collapse;border-spacing: 0;}.test-selector:hover{height: 100%;left: 0;position: absolute;top: 0;width: 100%;color: red;}.test-selector::placeholder{height: 100%;left: 0;position: absolute;top: 0;width: 100%;color: red;}',
            'expected' => ' .foo{height: 100%;left: 0;position: absolute;top: 0;width: 100%;color: red;}& .foo .bar{height: 100%;left: 0;position: absolute;top: 0;width: 100%;color: red;}& table{border-collapse: collapse;border-spacing: 0;}&:hover{height: 100%;left: 0;position: absolute;top: 0;width: 100%;color: red;}&::placeholder{height: 100%;left: 0;position: absolute;top: 0;width: 100%;color: red;}',
            // phpcs:enable
        ];

        yield 'root custom properties' => [
            'selector' => '.test-selector',
            'actual' => '.test-selector{--foo: 100%;}',
            'expected' => '--foo: 100%;',
        ];

        yield 'root custom properties with multiple rules' => [
            'selector' => '.test-selector',
            'actual' => '.test-selector{--foo: 100%;--bar: 100%;}',
            'expected' => '--foo: 100%;--bar: 100%;',
        ];

        yield 'root with pseudo elements' => [
            // phpcs:disable
            'selector' => '.test-selector',
            'original' => '.test-selector{height: 100%;width: 100%;color: red;}.test-selector:hover {color: red;}.test-selector::placeholder {color: red;}',
            'expected' => 'height: 100%;width: 100%;color: red;&:hover {color: red;}&::placeholder {color: red;}',
            // phpcs:enable
        ];

        yield 'with nested selector' => [
            // phpcs:disable
            'selector' => '.test-selector',
            'original' => '.test-selector{color: red; margin: auto;}.test-selector.one{color: blue;}.test-selector .two{color: green;}',
            'expected' => 'color: red; margin: auto;&.one{color: blue;}& .two{color: green;}',
            // phpcs:enable
        ];
    }

    public static function newStyleProvider(): iterable
    {
        yield 'root element' => [
            'selector' => '.test-selector',
            'actual' => '.test-selector{height: 100%;}',
            'expected' => 'height: 100%;',
        ];

        yield 'root element with multiple rules' => [
            'selector' => '.test-selector',
            'actual' => '.test-selector{height: 100%;width: 100%;color: red;}',
            'expected' => 'height: 100%;width: 100%;color: red;',
        ];

        yield 'root element with multiple rules and pseudo class' => [
            'selector' => '.test-selector',
            'actual' => '.test-selector{height: 100%;width: 100%;color: red;}.test-selector:hover {color: red;}',
            'expected' => 'height: 100%;width: 100%;color: red;&:hover{color: red;}',
        ];

        yield 'root element with multiple rules and pseudo class and pseudo element' => [
            // phpcs:disable
            'selector' => '.test-selector',
            'actual' => '.test-selector{height: 100%;width: 100%;color: red;}.test-selector:hover {color: red;}.test-selector::placeholder {color: red;}',
            'expected' => 'height: 100%;width: 100%;color: red;&:hover{color: red;}&::placeholder{color: red;}',
            // phpcs:enable
        ];

        yield 'pseudo single class' => [
            'selector' => '.test-selector',
            'actual' => '.test-selector:hover {color: red;}',
            'expected' => ':hover{color: red;}',
        ];

        yield 'pseudo single class with multiple rules' => [
            'selector' => '.test-selector',
            'actual' => '.test-selector:hover {color: red;height: 100%;}',
            'expected' => ':hover{color: red;height: 100%;}',
        ];

        yield 'pseudo single class with multiple rules and pseudo element' => [
            'selector' => '.test-selector',
            'actual' => '.test-selector:hover {color: red;height: 100%;}.test-selector::placeholder {color: red;}',
            'expected' => ':hover{color: red;height: 100%;}&::placeholder{color: red;}',
        ];

        yield 'simple pseudo element ::placeholder ' => [
            'selector' => '.test-selector',
            'actual' => '.test-selector::placeholder {color: red;}',
            'expected' => '::placeholder{color: red;}',
        ];

        yield 'simple pseudo element with multiple rules ::placeholder ' => [
            'selector' => '.test-selector',
            'actual' => '.test-selector::placeholder {color: red;height: 100%;}',
            'expected' => '::placeholder{color: red;height: 100%;}',
        ];

        yield 'root element with pseudo element' => [
            'selector' => '.test-selector',
            'actual' => '.test-selector{height: 100%;}.test-selector::placeholder {color: red;}',
            'expected' => 'height: 100%;&::placeholder{color: red;}',
        ];

        yield 'mixed css example' => [
            'selector' => '.test-selector',
            'actual' => '.test-selector{height: 100%;}.test-selector .foo{color: red;}',
            'expected' => 'height: 100%;& .foo{color: red;}',
        ];

        yield 'mixed css example with multiple rules' => [
            'selector' => '.test-selector',
            'actual' => '.test-selector{height: 100%;width: 100%;}.test-selector .foo{color: red;height: 100%;}',
            'expected' => 'height: 100%;width: 100%;& .foo{color: red;height: 100%;}',
        ];

        yield 'simple css example' => [
            'selector' => '.test-selector',
            'actual' => '.test-selector .foo{height: 100%;left: 0;position: absolute;top: 0;width: 100%;}',
            'expected' => ' .foo{height: 100%;left: 0;position: absolute;top: 0;width: 100%;}',
        ];

        yield 'simple css example with multiple rules' => [
            // phpcs:disable
            'selector' => '.test-selector',
            'actual' => '.test-selector .foo{height: 100%;left: 0;position: absolute;top: 0;width: 100%;color: red;}.test-selector .foo .bar{height: 100%;left: 0;position: absolute;top: 0;width: 100%;color: red;}',
            'expected' => ' .foo{height: 100%;left: 0;position: absolute;top: 0;width: 100%;color: red;}& .foo .bar{height: 100%;left: 0;position: absolute;top: 0;width: 100%;color: red;}',
            // phpcs:enable
        ];

        yield 'simple css example with multiple rules and pseudo class' => [
            // phpcs:disable
            'selector' => '.test-selector',
            'actual' => '.test-selector .foo{height: 100%;left: 0;position: absolute;top: 0;width: 100%;color: red;}.test-selector .foo .bar{height: 100%;left: 0;position: absolute;top: 0;width: 100%;color: red;}.test-selector table{border-collapse: collapse;border-spacing: 0;}.test-selector:hover {height: 100%;left: 0;position: absolute;top: 0;width: 100%;color: red;}',
            'expected' => ' .foo{height: 100%;left: 0;position: absolute;top: 0;width: 100%;color: red;}& .foo .bar{height: 100%;left: 0;position: absolute;top: 0;width: 100%;color: red;}& table{border-collapse: collapse;border-spacing: 0;}&:hover{height: 100%;left: 0;position: absolute;top: 0;width: 100%;color: red;}',
            // phpcs:enable
        ];

        yield 'simple css example with multiple rules and pseudo class and pseudo element' => [
            // phpcs:disable
            'selector' => '.test-selector',
            'actual' => '.test-selector .foo{height: 100%;left: 0;position: absolute;top: 0;width: 100%;color: red;}.test-selector .foo .bar{height: 100%;left: 0;position: absolute;top: 0;width: 100%;color: red;}.test-selector table{border-collapse: collapse;border-spacing: 0;}.test-selector:hover{height: 100%;left: 0;position: absolute;top: 0;width: 100%;color: red;}.test-selector::placeholder{height: 100%;left: 0;position: absolute;top: 0;width: 100%;color: red;}',
            'expected' => ' .foo{height: 100%;left: 0;position: absolute;top: 0;width: 100%;color: red;}& .foo .bar{height: 100%;left: 0;position: absolute;top: 0;width: 100%;color: red;}& table{border-collapse: collapse;border-spacing: 0;}&:hover{height: 100%;left: 0;position: absolute;top: 0;width: 100%;color: red;}&::placeholder{height: 100%;left: 0;position: absolute;top: 0;width: 100%;color: red;}',
            // phpcs:enable
        ];

        yield 'root custom properties' => [
            'selector' => '.test-selector',
            'actual' => '.test-selector{--foo: 100%;}',
            'expected' => '--foo: 100%;',
        ];

        yield 'root custom properties with multiple rules' => [
            'selector' => '.test-selector',
            'actual' => '.test-selector{--foo: 100%;--bar: 100%;}',
            'expected' => '--foo: 100%;--bar: 100%;',
        ];

        yield 'root with pseudo elements' => [
            // phpcs:disable
            'selector' => '.test-selector',
            'original' => '.test-selector{height: 100%;width: 100%;color: red;}.test-selector:hover {color: red;}.test-selector::placeholder {color: red;}',
            'expected' => 'height: 100%;width: 100%;color: red;&:hover{color: red;}&::placeholder{color: red;}',
            // phpcs:enable
        ];

        yield 'with nested selector' => [
            // phpcs:disable
            'selector' => '.test-selector',
            'original' => '.test-selector{color: red; margin: auto;}.test-selector.one{color: blue;}.test-selector .two{color: green;}',
            'expected' => 'color: red;margin: auto;&.one{color: blue;}& .two{color: green;}',
            // phpcs:enable
        ];
    }
}
