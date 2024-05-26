.PHONY: test

test:
	vendor/bin/phpunit

.PHONY: cs-fix cs-check
cs-fix:
	composer cs-fix

cs-check:
	composer cs-check

phpstan:
	vendor/bin/phpstan
