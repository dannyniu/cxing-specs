<?= hc_H1("Library for I/O") ?>

> This chapter forms an integral part of "The Input/Output Module" -
> should "The Input/Output Module" be implemneted, this chapter along with any
> chapter constituting part of "The Input/Output Module" must be implemented
> in their entirity.

For the purpose of this chapter, the following definitions from the POSIX
standard apply:

- directory entry,
- EOF,
- FIFO,
- file,
- file descriptor,
- open file description,
- pipe,
- socket,

Additionally, a _file handle_ is anything that can be used to operate on files.
One file may have several file handles. This chapter define several types of
object that're file handles.

When a file is operated on from separate handles, the behavior is undefined.

**Note**: For example, in C, when standard input is being read through a `FILE *`
handle, and buffering is enabled, the subsequent file position of the
file descriptor (if implemented on top of one) is undefined - this can cause
issue when one program subsequently loads another (e.g. using one of the `exec`
functions) and the loaded program proceeds from an unexpected file position.
This is among the few undefined behaviors in <?= langname() ?>, and we choose
to not define its behavior due to its usage being arcane and lacking practicality.

When a directory entry is created as a result of calling one of the functions
that accesses the filesystem, barring security hardening by specific
implementations of this module, eventhough not a recommended practice, the
called function should not place access restriction beyond what's already
placed by system defaults.

**Note**: As an example of what previous paragraph means, function calls such
as `mkdir`, `mkfifo`, `open`, etc. should use the most liberal permission
on the created file - i.e. `0o777` for directories and `0o666` non-executable
files according to POSIX, with 'file mode creation mask' (i.e. umask)
clearing excess permissions as the said 'system default'. The previous
paragraph is normative to the extent not to forbid current latest evolving
security best practice.

<?= hc_H2("Simple Input/Output") ?>

```
subr input();
subr print(s);
```

The `input()` function is a subroutine that reads a line from the standard input,
stripping a single trailing line-feed `\n` byte, then if there is one,
a trailing carriage-return `\r` byte, then returns the resulting string.
On EOF a blessed `null` that uncasts to 0 is returned; on error, a blessed `null`
that uncasts to an implementation-defined status code is returned.

**TODO**: This implementation-defined status code is expected to be that of
the `errno` number. Details of this part is being decided.

The `print()` function is a subroutine that writes the string argument `s` to
the standard output, followed by a single line-feed `\n` byte. On success,
the number of bytes successfully written. A blessed `null` that uncasts to
an implementation-defined status code is returned on failure.

<?= hc_H2("Generic File") ?>

```
GenericFile(obj) := {
  method read(len),
  method write(s),
  method close(),
  method flush(),
  method setsync(b),
}
```

A `GenericFile` is the base type for file handle objects.

Its `read` method reads at most `len` bytes of data and returns it. On EOF, it
returns an empty string; on error, it returns a blessed `null` that uncasts to
an implementation-defined status code.

Its `write` method writes the string `s` to the file, and returns the number of
bytes actually written. On error, it returns a blessed `null` that uncasts to
an implementation-defined status code.

Its `close` method closes the file - any buffered content will be _committed_,
any resource consumed for operating the file will be released, any further use
of the file handle are invalid and results in error in an undefined way.

For any file, there may be several layers of buffering, two of which are defined
here (the rest are given acknowledgement).
1. The user-space buffering, which are committed by calling the `flush` method,
2. The system buffering, which can be disabled (or enabled) by calling `setsync`
   with `true` (or `false`).

The act of "committing" make it more likely that future access to the data
would succeed, such as writing data permanently to the disk. Further buffering,
such as those done by routers and switches for network sockets,
are out of the control of the program, and to some extent, the system.

<?= hc_H2("Regular Files") ?>

```
subr open(path, mode);
RegularFile(GenericFile) := {
  method lseek(offset, whence),
}
```

The `open` function is a subroutine that opens a file named by the `path`
argument, under the mode specified by the `mode` argument. The file to open
doesn't have to be a regular file, any type of file supported by the
implementation may be opened (e.g. FIFO, but not sockets).

The mode is made up of one of the following 4 major options:
- `0\r`: open for reading only,
- `0\w`: open for writing, truncate or create the file first,
- `0\R`: open for both reading and writing,
- `0\W`: open for both reading and writing, truncate or create the file first,

and modified by any combination of the following minor options:
- `0\a`: open for appending - i.e. write to the end of the file,
- `0\e`: the file handle won't be available to any program loaded by the
         current process (e.g. `O_CLOEXEC` - close-on-exec).
- `0\x`: cause the open to fail if the file already exists - if the open was
         successful, other opens elsewhere shall not succeed.

The `lseek` method adds `offset` to the position indicated by `whence`, and
returns the resulting file position:
- `0\SET`: from the beginning of the file - i.e. 0.
- `0\CUR`: from the current position of the file.
- `0\END`: from the end of the file.

<?= hc_H2("Unidirectional Communication") ?>

The types of files in this section are required to support communicating in
one direction, volunteer support for bidirection communication is not required.

```
subr mkfifo(path);
subr pipe();
```

The `mkfifo` function creates a FIFO - i.e. a pipe with a filesystem name.
On success, it returns `path`; on failure, it returns a blessed `null` that
uncasts to an implementation-defined status code.

The `pipe` function creates an anonymous pipe, and returns an object
with 2 members:
- `rd`, the reading end of the pipe, and
- `wr`, the writing end of the pipe.

Both of which are file handles. On failure, it returns a blessed `null` that
uncasts to an implementation-defined status code.

<?= hc_H2("Filesystem Operations") ?>

```
subr rename(old, new);
subr remove(path);
```

The function `rename` renames the `old` directory entry to the `new` name.
On success, `new` is returned, otherwise, a blessed `null` that uncasts to
an implementation-defined status code is returned.

The function `remove` causes the directory entry `path` to be no longer
accessible. On success, it returns `0`, otherwise, a blessed `null` that
uncasts to an implementation-defined status code is returned.

```
subr mkdir(path);
[subr opendir(path)] := {
  method readdir(),
  method rewinddir(),
  method closedir(),
}
```

The `mkdir` function creates a directory reachable at `path`. On success, `path`
is returned, otherwise, a blessed `null` that uncasts to an
implementation-defined status code is returned.

The `opendir` function opens a directory to enumerate its entries. On success,
a _directory handle_ is returned, otherwise, a blessed `null` that uncasts to
an implementation-defined status code is returned.

The `readdir` method returns a string naming the directory entry at the current
_directory position_, and advancing it. The directory position of a directory
handle is an opaque internal concept of directory handle. The `rewinddir`
resets the directory position to the state it was when it was opened and before
any call to `readdir` were made.

The `closedir` function release any resource used by the directory handle.
Any further use of the directory handle are invalid and results in error
in an undefined way.
