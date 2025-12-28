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

The number of parameters between all declarations and the definition of a
function MUST be consistent - the order of the arguments in a function call
MUST be consistent with what's expected by the parameters of the function.
Furthermore, whether a function is a method or a subroutine.
The name of the parameters may be changed in the source code of a program.
Depending on the context, this may provide the benefit of both explanative
argument naming in declaration, and avoidance identifier collision in function
definition when the argument is appropriately renamed.

**Note**: Before 2025-10-27, there were FFI methods. This had been removed,
because methods are attached to properties of objects, and their prototypes
cannot be reliably determined unless all parameters are of uniform type,
only then, could the *number* of arguments be determined. As of 2025-11-03,
all FFI are removed - this is because impossibility with determining the
prototype of the said FFI functions when they're called from object properties.
