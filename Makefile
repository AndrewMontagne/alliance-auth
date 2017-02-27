INV=\033[7m
NC=\033[0m

.PHONY: all clean build test package

all: clean build test
	@which composer > /dev/null

clean:
	@echo -e '\n${INV}###  CLEAN  ###${NC}\n'
	rm -rf ./build

build:
	@echo -e '\n${INV}###  BUILD  ###${NC}\n'
	composer update

test:
	@echo -e '\n${INV}###  TESTS  ###${NC}\n'
	~/.config/composer/vendor/bin/phpunit ./src/test/php/

package: clean build
	@echo -e '\n${INV}### PACKAGE ###${NC}\n'
	mkdir build
	composer install --no-dev
	~/.config/composer/vendor/bin/box build