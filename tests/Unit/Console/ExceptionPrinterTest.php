<?php namespace Tarsana\Command\Tests\Unit\Console;

use PHPUnit\Framework\TestCase;
use Tarsana\Command\Console\ExceptionPrinter;
use Tarsana\Syntax\Exceptions\ParseException;
use Tarsana\Syntax\Factory as S;


class ExceptionPrinterTest extends TestCase {

    public function test_it_prints_generic_exception() {
        $e = new \Exception("Useful message");
        $p = new ExceptionPrinter;
        $this->assertEquals("<error>{$e}</error>", $p->print($e));
    }

    public function test_it_prints_parse_exception() {
        $p = new ExceptionPrinter;
        $e = new ParseException(S::number(), 'test', 0, 'Not a number!');
        $this->assertEquals(
            "<reset>Failed to parse <warn>'test'</warn> as <info>Number</info>",
            $p->print($e)
        );

        $syntax = S::object([
            'name' => S::string(),
            'age' => S::number()
        ]);
        try {
            $syntax->parse('foo');
        } catch(ParseException $e) {
            $this->assertEquals(
                "<reset>Failed to parse <warn>'foo'</warn> as <info>name:age</info> ".
                "<error>age is missing!</error><br>".
                "<reset>Failed to parse <warn>''</warn> as <info>Number</info>",
                $p->print($e)
            );
        }
    }
}
