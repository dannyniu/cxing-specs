<?php
  if( !($GLOBALS['langdefs'] ?? false) )
  {
    function langname(){ return "cxing"; }
    function namechoice(){ return <<<EOF
      Just as Java is a beautiful island in Indonesia, we wanted a name that
      pride ourselves as Earth-Loving Chinese here in Shanghai, therefore we
      choose to name our language after the National Nature Reserve Park of
      Changxing Island. However, the name is too long to be used directly, and
      "changx" looked too much like 'clang', so we simplified it to
      "<samp>cxing</samp>", which we find both pleasure in looking at it,
      and the name giving connotation with an information technology product.
    EOF;
    }
    // As a history-keeping policy, version records are never removed.
    // Versioning follow semantic versioning; revisions are added one after
    // another in form of variable redefinitions; each change of minor version
    // follow the last redefinition of their respective revision redefinition;
    // each change of major version follow the last redefinition of the minor
    // version redefinition. the assignment to `$spec_semver` comes last to
    // receive the latest defined value.
    $spec_majver = "0"; # 2025-08-31.
    $spec_minver = "1"; # 2025-08-31.
    $spec_revver = "1"; # 2025-08-31.
    $spec_revver = "2"; # 2025-09-03.

    $spec_semver = "$spec_majver.$spec_minver.$spec_revver";
    $GLOBALS['langdefs'] = true;
  }
