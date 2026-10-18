# Maintaining the documentation

[Contributing](../CONTRIBUTING.md)

## Information structure

Keep [docs/README.md](../docs/README.md) as the user documentation index. Getting
started chapters teach one complete workflow; configuration and organization
chapters explain authoring; asset and automation chapters address concrete tasks.
Reference pages collect signatures, command options, constraints, and migration
details.

The root README introduces the package and provides one working example. It
should link to the full guides rather than duplicate entire chapters.

Use descriptive filenames and relative Markdown links. Old numbered chapters
are navigation pages pointing to their replacements, preserving existing links
without retaining a second copy of the instructions.

## Examples

- Use the current `ItalyStrap\ThemeJsonGenerator` public namespace.
- State whether a snippet is a complete entrypoint or belongs inside its callable.
- Reuse the example theme's `primary` color, `body` font size, and dark variation
  where useful. Identify examples that require classes from an earlier chapter.
- Register presets before referring to their keys.
- Show relevant output excerpts and label them as excerpts when incomplete.
- Choose a pinned example schema and explain that readers must target their own
  WordPress version. Do not present API coverage as universal runtime support.
- Keep directory attributes relative to the declaring file; distinguish flag
  paths that are relative to the current working directory.
- Separate rejected-input examples from runnable valid examples.

## Verify the documented behavior

Check command definitions and middleware as well as help output: accepted options
can exist without implemented behavior. In particular, retain accurate notes for
`init`, `dump --validate`, and validator exit codes until implementation changes.

Execute complete examples against the current project autoloader. Use temporary
theme directories to verify generation, selection, previews, and asset exports
without overwriting project files. Check that generated JSON values match the
examples, not merely that PHP parses.

Check relative links and heading anchors, JSON syntax, PHP syntax, and fenced
code blocks. For Markdown-only work these targeted checks are more useful than
running unrelated application suites.

Update the relevant task guide and reference page together when behavior changes.
Keep historical review reports, generated inventories, and local refactoring notes
out of the user guide navigation.
