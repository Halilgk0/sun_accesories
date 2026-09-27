#!/bin/sh
set -e

# The image ships a migrated and seeded SQLite database so the store works
# without an external Postgres. The container filesystem is not durable, so the
# copy lives in /tmp and is recreated whenever a new instance starts: browsing
# always works, while baskets and orders last only as long as the instance.
#
# Point DB_CONNECTION at pgsql (with a connection string) and this is skipped —
# the managed database is then the only source of truth.
if [ "${DB_CONNECTION:-sqlite}" = "sqlite" ]; then
    SEED_DB="/app/database/seeded.sqlite"
    LIVE_DB="${DB_DATABASE:-/tmp/database.sqlite}"

    if [ -f "$SEED_DB" ] && [ ! -f "$LIVE_DB" ]; then
        cp "$SEED_DB" "$LIVE_DB"
    fi
fi

exec "$@"
