<?php

/*
|--------------------------------------------------------------------------
| Composite Design Pattern - Implementation Example
|--------------------------------------------------------------------------
| This example demonstrates the Composite Design Pattern, which allows you
| to treat individual objects and compositions of objects uniformly. This is 
| particularly useful for representing part-whole hierarchies or tree structures.
|--------------------------------------------------------------------------
| @category  Design Pattern
| @package   Structural/Composite
| @version   1.0.0
| @license   MIT License
| @link      https://github.com/milendenev0912/design-patterns
|--------------------------------------------------------------------------
|
| Key Components:
| 1. **Component (Abstract Class)**: Declares the interface for all objects in the 
|    composition, both leaves and composites. Provides default implementations 
|    for managing child components.
|
| 2. **Leaf (Concrete Class)**: Represents the end objects in the composition. 
|    Performs actual work and cannot have children.
|
| 3. **Composite (Concrete Class)**: Represents a complex component that can 
|    have children (other leaves or composites). Manages child components and 
|    delegates operations to them.
|
| 4. **Client Code**: Works uniformly with all components (both simple and complex) 
|    via the common interface.
|
| Benefits:
| - **Uniformity**: Simplifies client code by allowing uniform treatment of individual
|   objects and object compositions.
| - **Flexibility**: Easily add new types of components without modifying existing code.
| - **Tree Structures**: Ideal for tree-like structures such as file systems, menus, etc.
*/

namespace Structural\Composite;

/**
 * The base Component class declares common operations for both simple and
 * complex objects of a composition.
 */
abstract class Component
{
    /**
     * @var Component|null
     */
    protected $parent;

    /**
     * Optionally, the base Component can declare an interface for setting and
     * accessing a parent of the component in a tree structure. It can also
     * provide some default implementation for these methods.
     */
    public function setParent(?Component $parent)
    {
        $this->parent = $parent;
    }

    public function getParent(): Component
    {
        return $this->parent;
    }

    /**
     * In some cases, it would be beneficial to define the child-management
     * operations right in the base Component class. This way, you won't need to
     * expose any concrete component classes to the client code, even during the
     * object tree assembly. The downside is that these methods will be empty
     * for the leaf-level components.
     */
    public function add(Component $component): void { }

    public function remove(Component $component): void { }

    /**
     * You can provide a method that lets the client code figure out whether a
     * component can bear children.
     */
    public function isComposite(): bool
    {
        return false;
    }

    /**
     * The base Component may implement some default behavior or leave it to
     * concrete classes (by declaring the method containing the behavior as
     * "abstract").
     */
    abstract public function operation(): string;
}

/**
 * The Leaf class represents the end objects of a composition. A leaf can't have
 * any children.
 *
 * Usually, it's the Leaf objects that do the actual work, whereas Composite
 * objects only delegate to their sub-components.
 */
class Leaf extends Component
{
    public function operation(): string
    {
        return "Leaf";
    }
}

/**
 * The Composite class represents the complex components that may have children.
 * Usually, the Composite objects delegate the actual work to their children and
 * then "sum-up" the result.
 */
class Composite extends Component
{
    /**
     * @var \SplObjectStorage
     */
    protected $children;

    public function __construct()
    {
        $this->children = new \SplObjectStorage();
    }

    /**
     * A composite object can add or remove other components (both simple or
     * complex) to or from its child list.
     */
    public function add(Component $component): void
    {
        $this->children->attach($component);
        $component->setParent($this);
    }

    public function remove(Component $component): void
    {
        $this->children->detach($component);
        $component->setParent(null);
    }

    public function isComposite(): bool
    {
        return true;
    }

    /**
     * The Composite executes its primary logic in a particular way. It
     * traverses recursively through all its children, collecting and summing
     * their results. Since the composite's children pass these calls to their
     * children and so forth, the whole object tree is traversed as a result.
     */
    public function operation(): string
    {
        $results = [];
        foreach ($this->children as $child) {
            $results[] = $child->operation();
        }

        return "Branch(" . implode("+", $results) . ")";
    }
}

/**
 * The client code works with all of the components via the base interface.
 */
function clientCode(Component $component)
{
    // ...

    echo "RESULT: " . $component->operation();

    // ...
}

/**
 * This way the client code can support the simple leaf components...
 */
$simple = new Leaf();
echo "Client: I've got a simple component:\n";
clientCode($simple);
echo "\n\n";

/**
 * ...as well as the complex composites.
 */
$tree = new Composite();
$branch1 = new Composite();
$branch1->add(new Leaf());
$branch1->add(new Leaf());
$branch2 = new Composite();
$branch2->add(new Leaf());
$tree->add($branch1);
$tree->add($branch2);
echo "Client: Now I've got a composite tree:\n";
clientCode($tree);
echo "\n\n";

/**
 * Thanks to the fact that the child-management operations are declared in the
 * base Component class, the client code can work with any component, simple or
 * complex, without depending on their concrete classes.
 */
function clientCode2(Component $component1, Component $component2)
{
    if ($component1->isComposite()) {
        $component1->add($component2);
    }
    echo "RESULT: " . $component1->operation();

}

echo "Client: I don't need to check the components classes even when managing the tree:\n";
clientCode2($tree, $simple);