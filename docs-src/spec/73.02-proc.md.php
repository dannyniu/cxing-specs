<?= hc_H1("Library for Process Management") ?>

> This chapter forms an integral part of "The Process Management Module" -
> should "The Process Management Module" be implemneted, this chapter along
> with any chapter constituting part of "The Process Management Module" must be
> implemented in their entirity.

> This module depend on "The Input/Output Module", should this module be
> implemented, "The Input/Output Module" must also be implemented.

```
[subr CmdInterp()] := {
  method __copy__(),
  method __final__(),
  method Argc(n),
  method Argv(v),
  method Envp(v),
  method ObtainPipeForStdin(),
  method ObtainPipeForStdout(),
  method ObtainPipeForStderr(),
  method SetSourceForStdin(fp),
  method SetDestForStdout(fp),
  method SetDestForStderr(fp),
  method SetCwd(path),
  [method Exec()] := {
    method __get__(k),
    method __copy__(),
    method __final__(),
    method Wait(),
    method Terminate(),
    method Kill(),
    method Stop(),
    method Continue(),
  },
};
```

The `CmdInterp` function creates a _preparation context_ used for executing a program.

The `Argc` method specifies the number of arguments to be received by
the invoked program.

The `Argv` method passes the argument `v` as an array consisting of an
ordered set of strings, terminated with a `null` as the "argument vector" (i.e.
the `argv` parameter to the C `main` function) to the context.

**Note**: The `Argc` method and the trailing `null` requirement are newly
added on 2026-06-08. Because improperly constructed argument and environment
arrays (more so for argument arrays) may cause undefined behavior in launched
processes, these are intended as consistency checks to guard against those.

**Note**: An alternative, known as 'rotten semantic' was considered, where
objects with broken invariants are rejected by failure critical APIs such as
these, but is not adopted for the time being, due to potential complexity.

The `Envp` method passes the argument `v` as a string-keyed object consisting
of a set of strings as the "environment variables" (i.e. available through
the `getenv` function in C) to the context.

The `ObtainPipeFor*` functions create pipes and attach appropriate reading
or writing end to the standard input/output/error of the child process, and
closing unused end in respective the process.

The `SetSourceForStdin` method sets the file handle `fp` as the reading source
for standard input of the new process. The `SetDestFor*` methods set `fp`
as the writing destination for standard output and standard error respectively.

The `SetCwd` method sets the initial value for the current working directory
for the new process.

On success, the functions `Argv`, `Envp`, `ObtainPipesFor*` 
and `Set{Source,Dest}For*` functions returns the preparation context, allowing
successive operations to be chained. On error, a blessed `null` that uncasts
to an implementation-defined status code is returned.

The `Exec` method executes and returns a _process handle_, or a blessed `null`
that uncasts to an implementation-defined status code is returned. If the number
of string arguments don't match the argument count specified in the `Argc` method,
or if the string arguments array is not terminated by a `null`, This function
shall also fail.

Because of the different ways a process is created on different platforms (e.g.
POSIX `fork`/`exec` vs Win32 `CreateProcess`), the error code uncasted from 
this function may contain values from namespace other than the `errno` values.
The differenciation of error codes from different namespaces are specified
in <?= hcNamedSection("Error Code Namespace") ?>.

The `__get__` method of the process handle is used to retrieve a few
non-type-associated properties:
- `infile`: The writing end of the pipe for standard input -
  `null` if `ObtainPipeForStdin` wasn't called when creating the process.
- `outfile`: The reading end of the pipe for standard output - 
  `null` if `ObtainPipeForStdout` wasn't called when creating the process.
- `errfile`: The reading end of the pipe for standard error -
  `null` if `ObtainPipeForStderr` wasn't called when creating the process.
  
The `Wait` method blocks the calling thread until the process referred to
by the process handle terminates, and returns its exit status.

Because of the retention of status information, the process referred to by the 
process handle could stay in some "zombie" form. And on Windows, the process
handle is one of those handles that needs to be closed (by calling `CloseHandle`
on them). As such, forgetting to eventually call this function will result in
resource leak.

The `Terminate` method terminates the process referred to by the process handle
The `Kill` method serves a similar function, but do it more forcibly, without
giving a chance for the process to do any cleanup.

The `Stop` method and `Continue` method stops (i.e. pauses) and continues the
execution of the process refered to by the process handle.

Both the preparation context and the process handle are exclusive object types,
meaning they cannot be accessed concurrently from multiple threads.

**TODO**: `chdir`, `getenv`, `main`, etc. for the current process.
