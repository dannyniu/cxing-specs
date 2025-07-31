<?php
  $draft = true;

  foreach( glob("*.inc.php") as $inc )
    include $inc;
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8"/>
<title><?= $draft ? "Draft" : "" ?> Specification for the
<?= $langname ?> Programming Language</title>
</head>
<body>

<?php if( $draft ){ # draft notice guard guard. ?>
<h1>Request For Comment on Draft Specification for
The <?= strtoupper($langname) ?> Programming Language.</h1>

<p>
Greetings all. This is a proposed draft of a proposed new programming language.
The BDFL of this project is DannyNiu/NJF. The intention of this request for
comments is to solicit ideas - advice, suggestions for improvement, as well as
critique on preceived defects.
<p>

<p>
While any idea are welcome, they're better received if they're accompanied
with counter-arguments, usage illustrations, and/or sketch of implementation,
yet the decision of adoption is ultimately made by the BDFL of the project.
</p>

<p>
You may submit your idea and/or queries by opening Issues at GitHub or Gitee,
both English and Chinese languages are accepted.
</p>

<div style="break-before: always">This page is intentionally left blank.</div>
<?php } ?>

<main>
<?php
  function inc($f)
  {
    global $draft;
    foreach( glob("*.inc.php") as $inc )
      include $inc;
    include $f;
    echo "\n\n";
  }

  // Good < Ok < DONE.

  // Intro //
  inc("00-intro.md.php"); # Ok(2025-07-31).
  inc("01-features.md.php"); # Ok(2025-07-31).

  // Language //
  inc("21-lex.md.php");
  inc("22-expressions.md.php");
  inc("23-statements.md.php"); # Good(2025-07-31).
  inc("24-functions.md.php");
  inc("25-translation-unit-interface.md.php");
  inc("31-langsem.md.php"); # Pending Confirmation.
  inc("32-objdef.md.php"); # Good(2025-07-31).
  inc("33-numerics.md.php");
  inc("34-rtsem.md.php");

  // Standard Library //
  inc("70-standard-library.md.php");
  inc("71.01-string.md.php");
  inc("71.02-struct.md.php");
  inc("71.03-types.md.php");

  # Outstanding tasks:
  # 1. full object run-time interoperability across implementations, 2025-07-30: made a page on ABI, WIP.
  # 2. declaration syntax, 2025-07-30: DONE.
  # 3. conditional and iterated statements. DONE
  # 4. statement labels. DONE
  # 5. finalizer.
  # 6. standard library.
?>
</main>

</body>
</html>
