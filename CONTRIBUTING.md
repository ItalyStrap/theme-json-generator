# Contributing

Bug reports, documentation improvements, and focused pull requests are welcome.
Describe the expected behavior, the actual behavior, and a minimal example when
reporting a problem.

For theme usage, start with [the documentation](docs/README.md). The pages below
are for work on the generator itself:

- [Architecture and source layout](contributing/architecture.md)
- [Maintaining the documentation](contributing/documentation.md)

## Development environment

The project uses the Docker setup under `.docker/` and Make shortcuts. Configure
that environment before running the suites. Commands may start its containers.
Use Make rather than invoking Composer test scripts directly.

```shell
make up
make tests
```

## Required checks

| Command | Purpose |
| --- | --- |
| `make unit` | Unit tests |
| `make integration` | Integration tests |
| `make functional` | Functional CLI tests |
| `make tests` | All three suites |
| `make cs` | Code style checks |
| `make stan` | Static analysis |
| `make qa` | Style, static analysis, and test suites |

After modifying PHP, run `make tests`. After completing a bug fix or feature,
run `make qa`. Documentation-only changes should verify examples, relative links,
and described behavior; a complete application suite is not needed merely for
prose changes.

Ignore the `ts/` folder for this PHP project workflow. Follow applicable local
`AGENTS.md` instructions when working in the repository.

## Schema API inventory

Run:

```shell
make report/api-inventory
make report/api-inventory/assert
```

The report tooling lives under `report/` and writes Markdown and CSV inventories
there. The assertion target fails when its coverage checks are not clean.
`ThemeSchemaCoverage` attributes map typed methods to the report's schema paths.
This tooling checks schema/API coverage; it does not prove compatibility with
every released WordPress version.

## Changes for review

Keep changes focused, document behavior changes, and update relevant examples
when API or command behavior changes. Include the checks performed and their
results in the pull request. Separate historical review notes and refactoring
plans from user-facing guides.
