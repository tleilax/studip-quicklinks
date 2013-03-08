build: css js

css:
	recess --compress assets/quicklinks.css > assets/quicklinks.min.css

js: assets/patch.js assets/studip-modal.js

%.js: %.coffee
	coffee --compile --print --bare $< > $@
	uglifyjs $@ --mangle --compress > `echo $@ | sed 's/\.js/.min.js/g'`
