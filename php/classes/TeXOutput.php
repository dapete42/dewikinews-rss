<?php

/*
  Note: This file uses UTF8 encoding. You might encounter problems if you
  don't use that encoding.

  Copyright (c) 2006 Peter Schlömer

  Released under the GNU General Public License (GPL), version 2. See
  LICENSE for the full text.
*/

include_once('Article.php');
include_once('Output.php');

class TeXOutput extends Output {

  protected function contentType() {
    return "text/plain";
  }

  protected function outputStart($articles) {
    print '\documentclass[DIV12,11pt,headsepline]{scrartcl}

\usepackage{ae}
\usepackage[T1]{fontenc}
\usepackage[ngerman]{babel}

\usepackage{multicol}
\usepackage{scrpage2}

\begin{document}

\pagestyle{scrheadings}
\ihead{\textsc{' . $this->converter->convertedText($this->title) . '}}
\ohead{}

\thispagestyle{plain}

\begin{center}
\textsc{\Huge ' . $this->converter->convertedText($this->title) . '}

\textbf{' . $this->converter->convertedText($this->description) . '}
\end{center}

';
  }

  protected function outputEnd($articles) {
    print "\end{document}\n";
  }

  protected function outputArticle(Article $article, $articles) {

//    print '\begin{multicols}{2}[\addsec{' . $this->converter->convertedText($article->getTitle()) . "}]\n\n";

    print '\addsec{' . $this->converter->convertedText($article->getTitle()) . "}\n\n";

    print '\textbf{' . $this->converter->convertedText($article->getPublished()->getLocaleDate('de', '%d. %B %Y, %H:%M %Z')) . "} -- \n";

    print $this->converter->convertedText($article->getText());

//    print "\n\n\end{multicols}\n\n";

  }

}

?>