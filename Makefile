VENDOR_BIN=./vendor/bin
.DEFAULT_GOAL := help
.PHONY: help, test

help:           ## Displays this help messagemake
	@fgrep -h "##" $(MAKEFILE_LIST) | fgrep -v fgrep | sed -e 's/\\$$//' | sed -e 's/##//'

test:           ## Runs tests
	$(VENDOR_BIN)/phpunit

clean:
	rm ./tests/resources/test.ics