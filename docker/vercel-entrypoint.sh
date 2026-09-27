#!/bin/sh
set -e

# Nothing here may block: until this script reaches exec, the instance answers
# no requests, and anything routed to it waits. Schema changes therefore run
# as a separate step against the managed database, never on the way up.

if [ "${DB_CONNECTION:-sqlite}" = "sqlite" ]; then
    # No managed database is attached, so fall back to the migrated and seeded
    # SQLite file baked into the image. The container filesystem is not durable
    # and each instance gets its own copy, so this serves the catalogue but
    # cannot hold a basket reliably.
    SEED_DB="/app/database/seeded.sqlite"
    LIVE_DB="${DB_DATABASE:-/tmp/database.sqlite}"

    if [ -f "$SEED_DB" ] && [ ! -f "$LIVE_DB" ]; then
        cp "$SEED_DB" "$LIVE_DB"
    fi
fi

exec "$@"
