<?php


namespace LastCall\Patterns\Core\Tests\Variable;


use LastCall\Patterns\Core\Variable\ScalarType;
use LastCall\Patterns\Core\Variable\VariableSet;
use PHPUnit\Framework\TestCase;

class VariableSetTest extends TestCase {

  private function getTestSet() {
    return new VariableSet([
      'foo' => new ScalarType('string', 'foo'),
      'empty' => new ScalarType('string')
    ]);
  }

  public function testHas() {
    $set = $this->getTestSet();
    $this->assertTrue($set->has('foo'));
    $this->assertTrue($set->has('empty'));
  }

  public function testAppliesGlobals() {
    $set = $this->getTestSet();
    $globals = new VariableSet([
      'foo' => new ScalarType('string', 'bar'),
      'empty' => new ScalarType('string', 'baz'),
      'baz' => new ScalarType('string', 'baz'),
    ]);
    $merged = $set->applyGlobals($globals);
    $this->assertEquals(new VariableSet([
      'foo' => new ScalarType('string', 'foo'),
      'empty' => new ScalarType('string', 'baz')
    ]), $merged);
  }

  /**
   * @expectedException \LastCall\Patterns\Core\Exception\InvalidVariableException
   * @expectedExceptionMessage Cannot merge sets - Expected empty to be an string, got an integer
   */
  public function testDoesNotAllowTypeChangeOnGlobals() {
    $set = $this->getTestSet();
    $globals = new VariableSet([
      'empty' => new ScalarType('integer', 2),
    ]);
    $set->applyGlobals($globals);
  }

  public function testManifest() {
    $data = $this->getTestSet()->manifest();
    $this->assertEquals([
      'foo' => 'foo',
    ], $data);
  }
}