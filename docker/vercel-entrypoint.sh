#!/bin/sh
set -e

DRIVER="${DB_CONNECTION:-sqlite}"

if [ "$DRIVER" = "sqlite" ]; then
    # No managed database is attached, so fall back to the migrated and seeded
    # SQLite file baked into the image. The container filesystem is not durable
    # and each instance gets its own copy, so this serves the catalogue but
    # cannot hold a basket reliably.
    SEED_DB="/app/database/seeded.sqlite"
    LIVE_DB="${DB_DATABASE:-/tmp/database.sqlite}"

    if [ -f "$SEED_DB" ] && [ ! -f "$LIVE_DB" ]; then
        cp "$SEED_DB" "$LIVE_DB"
    fi
else
    # Bring the managed database up to date on cold start. Vercel gives us no
    # separate release step, so this is the only place it can run.
    #
    # Neon's pooler runs PgBouncer in transaction mode, which does not get on
    # with the server-side prepared statements DDL uses, so migrations go over
    # the direct connection when one is published.
    MIGRATE_URL="${DATABASE_URL_UNPOOLED:-${DB_URL:-${DATABASE_URL:-}}}"

    # A failure here must not crash-loop the container: when two instances cold
    # start together one loses the race to create the tables, and it should
    # carry on and serve the site the winner just migrated.
    DB_URL="$MIGRATE_URL" php artisan migrate --force --no-interaction \
        || echo "[entrypoint] migrate did not complete" >&2

    # Every seeder uses updateOrCreate, so this is safe to repeat on each boot.
    DB_URL="$MIGRATE_URL" php artisan db:seed --force --no-interaction \
        || echo "[entrypoint] seed did not complete" >&2
fi

exec "$@"
