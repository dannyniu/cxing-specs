<?= hc_H1("Functions") ?>

```grammar
function-declaration % funcdecl
: "subr" identifier arguments-list statement % subr
| "method" identifier arguments-list statement % method
;

arguments-list % arglist
: "(" ")" % empty
| arguments-begin ")" % some
;

arguments-begin % args
: "(" identifier % base
| arguments-begin "," identifier % genrule
;

```

**Note**: As of 2025-12-26, all concepts of type keywords, operand attributes,
and annotation in general had been eliminated as unnecessary.

When the function body is `emptystmt`, the `function-declaration` declares a
function; when it's `brace`, it defines a function. The `this` keyword MUST NOT
appear in the function body of a subroutine.

When the end of the function body is reached without an explicit `return` phrase,
a Morgoth `null` is implicitly returned.

Whether a function is a method or a subroutine must agree across
all declarations and definition of a function. For declarations that are not
definition of the function, the number of parameters can vary. This permits
a limited form of overloading - limited because there can be only one
definition of a function, and it has only one parameter list.

Within the function body, the first parameter receives the value of the first
argument in the function call, the second parameter the second argument,
and so on. If there are more arguments than parameters, excess arguments are
ignored; conversely, if there are fewer arguments than parameters, those
parameters that didn't receive arguments assume the value of `null`.

**Note**: Before 2025-10-27, there were FFI methods. This had been removed,
because methods are attached to properties of objects, and their prototypes
cannot be reliably determined unless all parameters are of uniform type,
only then, could the *number* of arguments be determined. As of 2025-11-03,
all FFI are removed - this is because impossibility with determining the
prototype of the said FFI functions when they're called from object properties.
