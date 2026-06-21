<?= hc_H1(strtoupper(langname())." Sockets API") ?>

> This chapter forms an integral part of module "The Networking Module" -
> should "The Input/Output Module" be implemneted, this chapter along with any
> chapter constituting part of "The Input/Output Module" must be implemented
> in their entirity.

> This module depend on module "The Input/Output Module", should this module be 
> implemented, module "The Input/Output Module" must also be implemented.

<?= hc_H2("Additional Error Number Requirements") ?>

If the platform on which a <?= langname() ?> program runs uses separate namespace
for either the identifier or the value (or both) of error numbers for socket-specific
errors than the `errno` codes of other system-diagnosed errors, the implementation
shall map any socket-specific error codes to the namespace for `errno` codes.

**Note**: For example, on Windows, the Winsock 2 API have the `WSA` prefix to
error values such as `WSAEWOULDBLOCK`, `WSAEINTR`, etc. The implementation must
map those to `EWOULDBLOCK`, `EINTR`, etc. when they cast them into `null` for
failure returns.

<style>
  table.api-mapping {
    border-collapse:   separate;
    border-spacing:    3mm 1mm;
  }
</style>

<?= hc_H2("General") ?>

```
[Socket(GenericFile): subr socket(dom, typ, proto)] := {
  method send(str, flags),
  method recv(len, flags), // returns a string object.
  method sendto(str, flags, peer),
  method recvfrom(len, flags, peer), // returns a string object

  method shutdown(how),
  method bind(name),
  method connect(peer),
  method listen(backlog),
  method accept(), // returns a socket,

  method __get__(k),
  method __set__(k, v),
  method __copy__(),
  method __final__(),
}
```

The parameters `peer` and `name` for `sendto`, `recvfrom`, and `bind`, as well
as the return value from `accept` are 'socket address' objects described below.

The methods for a socket object return `null` that uncasts to `errno` codes
on failures. The correspondence between the socket methods and POSIX socket API
are as follow:

<table class="api-mapping">
  <thead>
    <tr>
    <th>Method Name
    <th>POSIX API
    <th>Return Value on Success
    <th>Special Return Values
  </thead>
  <tbody>
    <tr>
    <td>`send`
    <td>`send`
    <td>The number of bytes sent,
    <td>-
    <tr>
    <td>`recv`
    <td>`recv`
    <td>The received data as a string object,
    <td>0 on EOF.
    <tr>
    <td>`sendto`
    <td>`sendto`
    <td>The number of bytes sent,
    <td>-
    <tr>
    <td>`recvfrom`
    <td>`recvfrom`
    <td>The received data as a string object,
    <td>0 on EOF.
    <tr>
    <td>`shutdown`
    <td>`shutdown`
    <td>0
    <td>-
    <tr>
    <td>`bind`
    <td>`bind`
    <td>0
    <td>-
    <tr>
    <td>`connect`
    <td>`connect`
    <td>0
    <td>-
    <tr>
    <td>`listen`
    <td>`listen`
    <td>0
    <td>-
    <tr>
    <td>`accept`
    <td>`accept`
    <td>A socket for interacting with the accepted connection.
    <td>-
  </tbody>
</table>

The implementation shall define at least the following integer constants
corresponding respectively to those in the POSIX API:

- Address Families: `AF_INET`, `AF_INET6`, `AF_UNIX` (if platform supports), `AF_UNSPEC`,
- Socket types: `SOCK_STREAM`, `SOCK_DGRAM`,
- I/O flags: `MSG_NOSIGNAL`, `MSG_PEEK`, `MSG_WAITALL`,
- Others: `SHUT_RD`, `SHUT_RDWR`, `SHUT_WR`, `SOCK_CLOEXEC`.

The `__get__` and `__set__` methods of a socket object can be used to configure
and inspect socket options and properties.

<?= hc_H2("Socket Options and Properties") ?>

The `sockname` and `peername` properties are the socket addresses of the socket
object and its peer respectively, and are retrieved using the `__get__` method.

The options on the other hand consist of a 'level' and a 'name', separated by
a solidus `/`. Implementations shall support the following options, and may
support additional options, Boolean options are of type `long` and may assume
the value of either `true` or `false`.

<table class="api-mapping">
  <thead>
    <tr>
    <th>Option Name,
    <th>Configuration Value Type,
  </thead>
  <tbody>
    <tr>
    <td>`SOL_SOCKET/SO_ACCEPTCONN` (read-only)
    <td>Boolean
    <tr>
    <td>`SOL_SOCKET/SO_BROADCAST`
    <td>Boolean
    <tr>
    <td>`SOL_SOCKET/SO_DEBUG`
    <td>Boolean
    <tr>
    <td>`SOL_SOCKET/SO_DOMAIN` (read-only)
    <td>Socket Domain Enumeration,
    <tr>
    <td>`SOL_SOCKET/SO_DONTROUTE`
    <td>Boolean
    <tr>
    <td>`SOL_SOCKET/SO_ERROR` (read-only, cleared on read)
    <td>`long`
    <tr>
    <td>`SOL_SOCKET/SO_KEEPALIVE`
    <td>Boolean
    <tr>
    <td>`SOL_SOCKET/SO_LINGER`
    <td>Socket Linger Object
    <tr>
    <td>`SOL_SOCKET/SO_OOBINLINE` (read-only)
    <td>Boolean (always `true`, see rationale below).
    <tr>
    <td>`SOL_SOCKET/SO_PROTOCOL`
    <td>Boolean
    <tr>
    <td>`SOL_SOCKET/SO_RCVBUF`
    <td>`long`
    <tr>
    <td>`SOL_SOCKET/SO_RCVLOWAT`
    <td>`long`
    <tr>
    <td>`SOL_SOCKET/SO_RCVTIMEO`
    <td>`double` interpreted in seconds.
    <tr>
    <td>`SOL_SOCKET/SO_REUSEADDR`
    <td>Boolean
    <tr>
    <td>`SOL_SOCKET/SO_SNDBUF`
    <td>`long`
    <tr>
    <td>`SOL_SOCKET/SO_SNDLOWAT`
    <td>`long`
    <tr>
    <td>`SOL_SOCKET/SO_SNDTIMEO`
    <td>`double` interpreted in seconds.
    <tr>
    <td>`SOL_SOCKET/SO_TYPE` (read-only)
    <td>Socket Type Enumeration
    <tr>
    <td>`IPPROTO_IPV6/IPV6_JOIN_GROUP`
    <td>`ipv6_mreq`
    <tr>
    <td>`IPPROTO_IPV6/IPV6_LEAVE_GROUP`
    <td>`ipv6_mreq`
    <tr>
    <td>`IPPROTO_IPV6/IPV6_MULTICAST_HOPS`
    <td>`long`
    <tr>
    <td>`IPPROTO_IPV6/IPV6_MULTICAST_IF`
    <td>`ulong`
    <tr>
    <td>`IPPROTO_IPV6/IPV6_MULTICAST_LOOP`
    <td>Boolean
    <tr>
    <td>`IPPROTO_IPV6/IPV6_UNICAST_HOPS`
    <td>`long`
    <tr>
    <td>`IPPROTO_IPV6/IPV6_V6ONLY`
    <td>Boolean
    <tr>
    <td>`IPPROTO_TCP/TCP_NODELAY`
    <td>Boolean
  </tbody>
</table>

The newer RFC-9293 on the TCP protocol has advised against the use of
the 'urgent' flag in new applications. Although support for compatibility with
older applications that does use out-of-band from implementations are allowed,
<?= strtoupper(langname()) ?> is not one of those older language to begin with,
so we're omitting its support, and requiring that the `socket` subroutine to
set the `SO_OOBINLINE` option for all of its newly created sockets.

The `setsync` method of a socket (which is inherited from generic files)
shall be implemented using (i.e. on top of) the `TCP_NODELAY` option.

<?= hc_H2("Socket Address and Other Miscellaneous Types") ?>

Certain POSIX socket APIs expect pointers to structures that require language
bindings. Implementations may represent these structures as actual structures
in memory, and expose their members through the `__get__` and the `__set__`
methods, by reading from and writing to fields in the structures directly.
All subroutines that creates these so-called `DirectStructFieldAccess` types
shall initialize them to all-bit-zero before returning them.

```
[DirectStructFieldAccess] := {
  method __get__(k),
  method __set__(k, v),
  method __initset__(k, v),
  method __copy__(),
  method __final__(),
}

[sockaddr(DirectStructFieldAccess): subr sockaddr()]
```

- Mandatory Field(s):
  `sa_family`,
- Mandatory Fields for `AF_INET`:
  `sin_family`, `sin_port`, `sin_addr`
- Mandatory Fields for `AF_INET6`:
  `sin6_family`, `sin6_port`, `sin6_addr`.

The `__set__` and the `__get__` methods of socket address structure types shall
convert `sin_port`, `sin_addr`, and `sin6_port` between host byte order (setter
argument and getter return) and network byte order (underlying data backing)
when called; `sin6_addr` shall be a 16-byte string object.

```
[sock_linger(DirectStructFieldAccess): subr sock_linger()]
```

- Mandatory Fields:
  `l_onoff` (Boolean), `l_linger` (integer).

```
[ipv6_mreq(DirectStructFieldAccess): subr ipv6_mreq()]
```

- Mandatory Fields:
  `ipv6mr_multiaddr` (string object), `ipv6mr_interface` (integer).

<?= hc_H2("Hostname-Address Resolution") ?>

```
subr getaddrinfo(hostname, service, family, socktype, protocol, flags);
```

The `getaddrinfo` function finds (resolves) the socket addresses of a
service indicated by `service` residing at the host named by `hostname`.
These 2 arguments shall be string objects.

The `family`, `socktype`, `protocol`, and the `flags` parameters hints to
the resolver the kind of socket address being sought. They corresponds to
the respective fields in the POSIX `addrinfo` structure to the `hint` argument
to the POSIX `getaddrinfo` function. If no hinting is needed, they may be
specified as zero (The `AF_UNSPEC` address family has a numerical value of 0
as mandated by the standard).

On success, the function returns an array object containing resolved socket
addresses in the form of `addrinfo` structure whose fields may be consulted
to create sockets to initiate or receive connections.

On error, one of the `EAI_*` error codes, or in the case of `EAI_SYSTEM`,
one of the `errno` codes shall be casted into a `null` and returned.

The following member fields of the `addrinfo` structure shall be accessible:
- `ai_family`: the address family of the socket,
- `ai_socktype`: the socket type,
- `ai_protocol`: the protocol of the socket,
- `ai_addr`: the socket address object,
- `ai_canonname`: the "canonical name of service location".

**Note**: because `ai_flags` is the hint input, it's not part of mandated
readable output; since the result is returned in the form of array objects,
there's no need for `ai_next` field.

```
subr getnameinfo(sockaddr, flags);
```

The `getnameinfo` function shall return an object with 2 member fields:
- `node`: the hostname of the socket address object `sockaddr`,
- `service`: the service of the said socket address.

The implementation shall define at least the following integer constants
corresponding respectively to those in the POSIX API:
- `AI_PASSIVE`, `AI_CANONNAME`, `AI_NUMERICHOST`, `AI_NUMERICSERV`,
  `AI_V4MAPPED`, `AI_ALL`, `AI_ADDRCONFIG`,
- `NI_NOFQDN`, `NI_NUMERICHOST`, `NI_NAMEREQD`, `NI_NUMERICSERV`,
  `NI_NUMERICSCOPE`, `NI_DGRAM`.

<?= hc_H2("Synchronous Multiplexing") ?>

```
[subr PollFDs(n)] := {
  [pollfd(DirectStructFieldAccess): method __get__(i)] :={
    __get__(k),
    __set__(k, v),
  },
  method __set__(i, pollfd_elem),
  method __copy__(),
  method __final__(),
  method trunc(n),
}

subr poll(fdArray, timeout);
```

The `PollFDs` function creates and returns an array of _n_ `pollfd` structures.

The `__get__` method of this array returns the _i_'th element (in 0-based indexing)
which is a `pollfd` structure. If this structure didn't go out of scope
before the PollFDs array, then behavior of accessing it is undefined.
(This permits it be implemented as weak reference to the array.)

The `__set__` element copies the content of the `pollfd_elem` argument
into the _i_'th element of the array.

The resource management of the PollFDs array are goverend by the `__copy__`
and the `__final__` methods. The destruction of the array shall have no effect
on the file handles it contains.

The `trunc` method may be used to resize the array. After shrinking the array,
excess file handles shall not be closed (i.e. they're considered weak reference),
and after growing the array, additional slots shall be zero-initialized with
the file handle field set to a distinguished non-valid value.

A `pollfd` structure shall allows the setting of the following fields:
- `fd`: the file handle. Because of the need to convert it from a CXING file
  handle to a system handle, certain information may be lost preventing it
  from being retrieved back using the `__get__` method.
- `events`: the IO event that the program is interested in,
- `revents`: the IO event occured before polling completed.

The implementation shall define at least the following integer constants
corresponding respectively to those in the POSIX API:
- `POLLIN`, `POLLOUT`.
- `POLLERR`, `POLLHUP`, `POLLNVAL`.
