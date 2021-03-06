<?php

namespace Xhp;

class ClassLoader {
  protected $map;

  /**
   * Construct a new autoloader.
   *
   * Takes an optional initial map, as an
   * associative array from ":xhp-prefix" to "path/"
   * similar to Composer's PSR-4 autoloader.
   */
  public function __construct(array $map = array()) {
    $this->map = $map;
  }

  /**
   * Add an additional path mapping
   *
   * @param string $prefix - XHP namespace prefix (e.g. ":foo")
   * @param string $path - Filesystem path (e.g. "tags/")
   * @param bool $hasOwnFolder - If XHP has its own folder
   */
  public function add($prefix, $path, $hasOwnFolder = false) {
    $this->map[$prefix] = ['path' => $path, 'hasOwnFolder' => $hasOwnFolder];
  }

  /**
   * Remove a previously added mapping
   *
   * @param string $prefix - XHP namespace prefix (e.g. ":foo")
   */
  public function remove($prefix) {
    unset($this->map[$prefix]);
  }

  /**
   * Enable SPL Autoload hook
   *
   * @param bool $prepend - Add loader to start or end
   */
  public function register($prepend = false) {
    spl_autoload_register([$this, 'loadClass'], true, $prepend);
  }

  /**
   * Disable SPL Autoload hook
   */
  public function unregister() {
    spl_autoload_unregister([$this, 'loadClass']);
  }

  /**
   * SPL Autoload callback
   */
  public function loadClass($class) {
    if ($filename = $this->findClass($class)) {
      require $filename;
      return true;
    }

    return false;
  }

  /**
   * Resolve a mangled XHP classname to a filepath
   *
   * @param string $class - Raw (mangled) classname (e.g. "xhp_foo__bar__baz")
   * @return string|false - Path to tag definition, or FALSE on unknown
   */
  public function findClass($class) {
    $xhpname = ':' . self::unmangle($class);
    if ($xhpname === ':') {
      return false;
    }

    foreach ($this->map as $prefix => $path_config) {
      $path = $path_config['path'];
      $hasOwnFolder = $path_config['hasOwnFolder'];

      if (($prefix === $xhpname) ||
          !strncasecmp($prefix . ':', $xhpname, strlen($prefix) + 1))
      {
        $parts = explode(':', substr($xhpname, strlen($prefix) + 1));
        $filename = array_pop($parts);

        $pathname = $path . implode('/', $parts) . ($hasOwnFolder ? '/'.$filename : '') . '/' . $filename;
        foreach (['.php', '.hh'] as $ext) {
          if (file_exists($pathname . $ext)) {
            return $pathname . $ext;
          }
        }
      }
    }
    return false;
  }

  /**
   * Translate a mangled XHP classname to a user-friendly tag
   *
   * @param string $class - Mangled name (e.g. "xhp_foo__bar__baz")
   * @return string - Demangled name (e.g. "foo:bar:baz")
   */
  public static function unmangle($class) {
    if (strncmp('xhp_', $class, 4)) {
      return false;
    }
    return str_replace(array('__', '_'), array(':', '-'), preg_replace('#^xhp_#i', '', $class));
  }

  /**
   * Translate a user-friendly tagname to a mangled classname
   *
   * @param string $tag - Demangled name (e.g. "foo:bar:baz")
   * @return string - Mangled name (e.g. "xhp_foo__bar__baz")
   */
  public static function mangle($tag) {
    return 'xhp_'.str_replace(array(':', '-'), array('__', '_'), $tag);
  }
}
