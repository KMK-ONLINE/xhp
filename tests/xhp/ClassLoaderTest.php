<?hh
namespace Xhp;

use PHPUnit_Framework_TestCase;

class ClassLoaderTest extends PHPUnit_Framework_TestCase {
  public function testClassLoader() {
    $l = new ClassLoader;
    $l->add(':foo', __DIR__ . '/../tags/');

    $expect = realpath(__DIR__ . '/../tags/bar/baz.php');
    $mangledName = ClassLoader::mangle('foo:bar:baz');
    $found = realpath($l->findClass($mangledName));
    $this->assertEquals($expect, $found);

    $l->register();
    $this->assertEquals('foo-bar-baz', (string)<foo:bar:baz/>);
  }
}
