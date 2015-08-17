build: js

js: assets/patch.js
	uglifyjs assets/html-additions.js --mangle --compress > assets/html-additions.min.js

%.js: %.coffee
	coffee --compile --print --bare $< > $@
	uglifyjs $@ --mangle --compress > `echo $@ | sed 's/\.js/.min.js/g'`
