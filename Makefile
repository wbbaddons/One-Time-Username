FILES = $(shell find files -type f)

all: be.bastelstu.max.wcf.otu.tar

be.bastelstu.max.wcf.otu.tar: files.tar acptemplates.tar *.xml LICENSE *.sql language/*.xml
	tar cvf be.bastelstu.max.wcf.otu.tar --numeric-owner --exclude-vcs -- files.tar acptemplates.tar *.xml LICENSE *.sql language/*.xml

files.tar: $(FILES)
	tar cvf files.tar --numeric-owner --exclude-vcs --transform='s,files/,,' -- $^

acptemplates.tar: acptemplates/*.tpl
	tar cvf acptemplates.tar --numeric-owner --exclude-vcs --transform='s,acptemplates/,,' -- acptemplates/*.tpl

templates.tar: templates/*.tpl
	tar cvf templates.tar --numeric-owner --exclude-vcs --transform='s,templates/,,' -- templates/*.tpl

clean:
	-rm -f files.tar
	-rm -f acptemplates.tar
	-rm -f files_wcf/js/Bastelstu.be.Chat.min.js

distclean: clean
	-rm -f be.bastelstu.max.wcf.otu.tar

.PHONY: distclean clean
