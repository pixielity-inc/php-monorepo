<div align="center">

<img src="https://gitlab.com/pixielity/laravel-laravel/framework/support/-/raw/main/.gitlab/banner.svg" alt="Support" width="100%">

</div>

The Pixielity Rain Support contains common classes relevant to supporting the other Pixielity Rain libraries. It adds the following features:

### Scaffolding

See the Scaffolding Commands section of the [Console documentation](https://pixielitycms.com/docs/console/commands).

### A true Singleton trait

A _true singleton_ is a class that can ever only have a single instance, no matter what. Use it in your classes like this:

    class MyClass
    {
        use \Pixielity\Support\Traits\Singleton;
    }

    $class = MyClass::instance();

### Global helpers

**input()**

Similar to `Input::get()` this returns an input parameter or the default value. However it supports HTML Array names. Booleans are also converted from
strings.

    $value = input('value', 'not found');
    $name = input('contact[name]');
    $city = input('contact[location][city]');

### Event emitter

Adds event related features to any class.

**Attach to a class**

    class MyClass
    {
        use Pixielity\Support\Traits\Emitter;
    }

**Bind to an event**

    $myObject = new MyClass;
    $myObject->bindEvent('cook.bacon', function(){
        echo 'Bacon is ready';
    });

**Trigger an event**

    // Outputs: Bacon is ready
    $myObject->fireEvent('cook.bacon');

**Bind to an event only once**

    $myObject = new MyClass;
    $myObject->bindEvent('cook.soup', function(){
        echo 'Soup is ready. Want more? NO SOUP FOR YOU!';
    }, true);

**Bind an event to other object method**

    $myObject->bindEvent('cook.eggs', [$anotherObject, 'methodToCookEggs']);

**Unbind an event**

    $myObject->unbindEvent('cook.bacon');
    $myObject->unbindEvent(['cook.bacon', 'cook.eggs']);
