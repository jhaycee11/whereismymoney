# Container name
APP_CONTAINER=whereismymoney

# Set correct permissions for Laravel
permissions:
	docker exec -u root $(APP_CONTAINER) chown -R www-data:www-data storage bootstrap/cache
	docker exec -u root $(APP_CONTAINER) chmod -R 775 storage bootstrap/cache
	@echo "✅ Laravel permissions applied successfully!"

# Shortcut to rebuild containers
rebuild:
	docker compose down
	docker compose up -d --build
	@echo "✅ Containers rebuilt successfully!"

# Clean up stopped containers and dangling images
clean:
	docker system prune -f
	@echo "🧹 Docker cleanup done!"

migrate:
	@echo "🛠 Running migrations..."
	docker exec -it $(APP_CONTAINER) php artisan migrate --force
	@echo "✅ Migrations done."

migrate-fresh:
	@echo "🛠 Running migrations..."
	docker exec -it $(APP_CONTAINER) php artisan migrate:fresh
	@echo "✅ Migrations fresh done."

# --------------------------------------------
# Create sessions table
sessions:
	@echo "📝 Creating sessions table..."
	docker exec -it $(APP_CONTAINER) php artisan session:table
	docker exec -it $(APP_CONTAINER) php artisan migrate --force
	@echo "✅ Sessions table created."

# --------------------------------------------
# Seed the database
seed:
	@echo "🌱 Seeding database..."
	docker exec -it $(APP_CONTAINER) php artisan db:seed --force
	@echo "✅ Database seeded."