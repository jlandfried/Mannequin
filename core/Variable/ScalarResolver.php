<?php


namespace LastCall\Mannequin\Core\Variable;


class ScalarResolver implements ResolverInterface {

  private static $supportedTypes = [
    'integer',
    'boolean',
    'string',
  ];

  public function validate(string $type, $value) {
    // @todo: Implement this method.
  }

  public function resolves(string $type): bool {
    return in_array($type, $this::$supportedTypes);
  }

  public function resolve(string $type, $value) {
    switch($type) {
      case 'integer':
        return (int) $value;
      case 'boolean':
        return (bool) $value;
      case 'string':
        return (string) $value;
    }
  }
}