jsonml
======

Provides an XML Schema for JSON-values and a parser to
 - cast PHP-values to schema-aware DOMElements and vice versa
 - validate DOMNodes against the schema.
   (Instances of DOMAttr can only be validated within the context of a DOMElement, not as a solitaire.)

The XML Schema is provided as a composer binary, and thus will be symlinked into the composer/bin-directory.
