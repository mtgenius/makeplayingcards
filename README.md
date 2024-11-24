# MakePlayingCards

Using Apache, PHP, the GD library, the Scryfall card image API, and Docker, the
`mtgenius/makeplayingcards` Docker image generates higher resolution (816px wide
by 1110px tall) _Magic: The Gathering_ card images with filled-in corners and
extended borders.

## Commands

The following commands are shortcuts for quickly manipulating a Docker container
named `makeplayingcards` on the `reverse-proxy` network.

- `yarn build` builds the Docker image.
- `yarn build-run` builds the Docker image, stops and removes the existing
  Docker container, and runs the new Docker container.
- `yarn run` runs the Docker container.
- `yarn rm` removes the existing Docker container.
- `yarn stop` stops the existing Docker container.

## Sponsor

If you are a fan of this project, you may
[become a sponsor](https://github.com/sponsors/quisido) via GitHub's Sponsors
Program.
