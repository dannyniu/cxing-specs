<?= hc_H1("Phrases") ?>

Between expressions and statements, there are phrases.

Phrases are like expressions, and have values, but due to grammatical
constraints, they lack the usage flexibility of expressions. For example,
phrases cannot be used as arguments to function calls, since phrases are
not comma-delimited; nor can they be assigned to variables, since assignment
operators binds more tightly than phrase delimiters. On the other hand, phrases
provides flexibility in combining full expressions in way that wouldn't
otherwise be expressive enough through expressions due to use of parentheses.

```grammar
and-phrase-ion % and_phrase_ion
: expressions-list "and" % exprlist_and
| expressions-list "_Then" % exprlist_then
| and-phrase-atom "and" % ionize_and
| and-phrase-atom "_Then" % ionize_then
;

and-phrase-atom % and_phrase_atom
: and-phrase-ion expressions-list % atomize
;

or-phrase-ion % or_phrase_ion
: expressions-list "or" % exprlist_or
| expressions-list "_Fallback" % exprlist_nullcoalesce
| control-flow-operator "or" % op_or
| control-flow-operator "_Fallback" % op_nullcoalesce
| control-flow-operator label "or" % labelledop_or
| control-flow-operator label "_Fallback" % labelledop_nullcoalesce
| "return" "or" % returnnull_or
| "return" "_Fallback" % returnnull_nullcoalesce
| "return" expression "or" % returnexpr_or
| "return" expression "_Fallback" % returnexpr_nullcoalesce
| and-phrase-atom "or" % andphra_or
| and-phrase-atom "_Fallback" % andphra_nullcoalesce
| or-phrase-atom "or" % ionize_or
| or-phrase-atom "_Fallback" % ionize_nullcoalesce
;

or-phrase-atom % or_phrase_atom
: or-phrase-ion and-phrase-atom % atomize
;

seq-phrase-molecule % seq_phrase_atom
: expressions-list ";" % exprlist
| control-flow-operator ";" % op
| control-flow-operator label ";" % labelledop
| "return" ";" % returnnull
| "return" expressions-list ";" % returnexpr
| or-phrase-atom ";" % orphra
;

phrase-stmt % phrase_stmt
: seq-phrase-molecule % degenerate
| and-phrase-ion seq-phrase-molecule % andphra
| or-phrase-ion seq-phrase-molecule % orphra
;
```

- `*_atom`: first, the ion is evaluated, if the result is `{STOP(value)}`,
  then the value of this phrase atom is `value`, otherwise the 2nd term
  (the `expressions-list` or the `and-phrase-atom`) is evaluated, and its
  value is the value of the phrase.
- `*_ion`: if the 1st term does not alter the control flow, then its value is
  evaluated, then, depending on the last token in the rewrite sequence:
  - if it's `"and"`, then `{CONTINUE}` is the result if the value is not `false`,
  - if it's `"or"`, then `{CONTINUE}` is the result if the value is `false`,
  - if it's `"_Then"`, then `{CONTINUE}` is the result if the value is not nullish.
  - if it's `"_Fallback"`, then `{CONTINUE}` is the result if and only if
    the value is nullish.
  - otherwise, `{STOP(value)}`, with `value` being the evaluated value of the
    1st term, becomes the result.
- `exprlist*`: the value of this term is that of the expression.

- `op`: Apply the flow-control operation to the inner-most applicable scope.
- `labelledop`: Apply the flow-control operation to the labelled statement scope.
- `returnnull`: Terminates the executing function.
  If the caller expected a return value, it'll be a Morgoth `null`.
- `returnexpr`: Terminates the executing function with
  return value being that of `expression`.

```grammar
control-flow-operator % flowctrlop
: "break" % break
| "continue" % continue
;
```

- `break`: Terminates the applicable loop.
- `continue`: Skip the remainder of the applicable loop body and
  proceed to the next iteration.

<?= hc_H1("Statements") ?>

```grammar
statement % stmt
: ";" % emptystmt
| identifier ":" statement % labelled
| phrase-stmt % phrase
| conditionals % cond
| while-loop % while
| do-while-loop % dowhile
| for-loop % for
| "{" statements-list "}" % brace
| declaration ";" % decl
;
```

- `emptystmt`: This does nothing in a function body.
- `labelled`: Identifies the statement with a label.
- `brace`: Executes `statements-list`.

<?= hc_H2("Condition Statements") ?>

```grammar
conditionals % condstmt
: predicated-clause % base
| predicated-clause "else" statement % else
;
```

- `else`: Executes `predicated-clause`, if none of its statement(s) were
  executed due to no predicate evaluated to true, then `statement` is executed.

```grammar
predicated-clause % predclause
: "if" "(" expressions-list ")" statement % base
| predicated-clause "elif" "(" expressions-list ")" statement % genrule
;
```

- `base`: Evaluate `expressions-list` (in expression phrase context as
  mentioned in the <?= hcNamedSection("Compounds") ?>),
  if it's true, then `statement` is executed, otherwise it's not executed.
- `genrule`: Executes `predicate-clause`, if none of its statement(s) were
  executed due to no predicate evaluated to true, then evaluate
  `expressions-list`, if that is still not true, then `statement` is not
  executed, otherwise, `statement` is executed.

<?= hc_H2("Loops") ?>

```grammar
while-loop % while
: "while" "(" expressions-list ")" statement % rule
;
```

- `rule`: To execute `rule`, evaluate `expressions-list`, if it's true,
  then execute `statement` and then execute `rule`.

```grammar
do-while-loop % dowhile
: "do" "{" statements-list "}" "while" "(" expressions-list ")" ";" % rule
;
```

- `rule`: To execute `rule`, execute `statements-list`, then evaluate
  `expressions-list`, if it's true, then execute `rule`.

```grammar
for-loop % for
: "for" "(" expressions-list ";"
            expressions-list ";"
            expressions-list ")" statement % classic

| "for" "(" declaration ";"
            expressions-list ";"
            expressions-list ")" statement % vardecl
;
```

- `classic`: Evaluate `expressions-list` before the first semicolon, then
  execute the for loop by invoking the "execute the for loop once" recursive
  procedure described later.
- `vardecl`: Evaluate `declaration`, then execute the for loop in a fashion
  similar to `classic`.

To execute the for loop once, evaluate `expressions-list` after the first
semicolon, if it's true, then `statement` is evaluated, then the
`expressions-list` after the second semicolon is evaluated, and the for loop is
executed once again. For the purpose of "proceeding to the next iteration" as
mentioned in `continue`, the `expressions-list` after the second semicolon is
not considered part of the loop body, and is therefore always executed before
proceeding to the next iteration.

The description here used the word "once" to describe the semantic of the loop
in terms of "functional recursion", where "functional" is in the sense of the
"functional programming paradigm".

<?= hc_H2("Statements List") ?>

```grammar
statements-list % stmtlist
: statement % base
| statements-list statement % genrule
;
```

- `base`: `statement` is executed, the semicolon is a delimitor.
- `genrule`: `statement-list` is first executed, then `statement` is executed.

<?= hc_H2("Declarations") ?>

Because the value of a variable that held integer value may transition to
`null` after being assigned the result of certain computation, the variable
needs to hold type information, as such, variables are represented conceptually
as "lvalue" native objects. (Actually, just value native objects, as their
scope and key can be deduced from context.)

```grammar
declaration % decl
: "decl" identifier % singledecl
| "decl" identifier "=" assign-expr % signledeclinit
| declaration "," identifier % declarelist1
| declaration "," identifier "=" assign-expr % declarelist2
;
```

- `singledecl`: Declares a variable with the spelling of the identifier as
  its name, and initialize its value to `null`.
- `singledeclinit`: Declares a variable with the spelling of the identifier as
  its name, and initialize its value to that of `assign-expr`.
- `declarelist1`: In addition to what's declared in `declaration`, declare
  another variable in a way similar to `singledecl`.
- `declarelist2`: In addition to what's declared in `declaration`, declare
  another variable in a way similar to `singledeclinit`.
