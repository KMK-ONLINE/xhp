<?hh
namespace Xhp;

use PHPUnit_Framework_TestCase;

class ClassLoaderTest extends PHPUnit_Framework_TestCase {
  public function testClassLoaderBase() {
    $l = new ClassLoader;
    $l->add(':foo', __DIR__ . '/../tags/');

    $expect = realpath(__DIR__ . '/../tags/bar/baz.php');
    $mangledName = ClassLoader::mangle('foo:bar:baz');
    $found = realpath($l->findClass($mangledName));
    $this->assertEquals($expect, $found);

    $l->register();
    $this->assertEquals('foo-bar-baz', (string)<foo:bar:baz/>);
  }

  public function testClassLoaderWhenEachFileHasItsFolder() {
    $l = new ClassLoader;
    $l->add(':foo', __DIR__ . '/../tags/', true);

    $expect = realpath(__DIR__ . '/../tags/bar/foo/foo.php');
    $mangledName = ClassLoader::mangle('foo:bar:foo');
    $found = realpath($l->findClass($mangledName));
    $this->assertEquals($expect, $found);

    $l->register();
    $this->assertEquals('foo-bar-foo', (string)<foo:bar:foo/>);
  }

  public function testClassLoaderWithoutPrefix() {
    $l = new ClassLoader;
    $l->add('', __DIR__ . '/../tags/', false);

    $expect = realpath(__DIR__ . '/../tags/bar/bar.php');
    $mangledName = ClassLoader::mangle('bar:bar');
    $found = realpath($l->findClass($mangledName));
    $this->assertEquals($expect, $found);

    $l->register();
    $this->assertEquals('bar-bar', (string)<bar:bar/>);
  }
}
