{% extends "layout.volt" %}
{% block body %}
<style>
.container_12 p {
border: 1px solid #666;
overflow: hidden;
padding: 10px 0;
text-align: center;
}
</style>

{% include "model/discussion" with ['DCZ': DATA_DISCUSSION] %}

<h1>Grid</h1>

<div class="container_12"><div class="grid_12"><p>grid_12</p></div><!-- end .grid_12 --><div class="clear"></div><div class="grid_1"><p>grid_1</p></div><!-- end .grid_1 --><div class="grid_11"><p>grid_11</p></div><!-- end .grid_11 --><div class="clear"></div><div class="grid_2"><p>grid_2</p></div><!-- end .grid_2 --><div class="grid_10"><p>grid_10</p></div><!-- end .grid_10 --><div class="clear"></div><div class="grid_3"><p>grid_3</p></div><!-- end .grid_3 --><div class="grid_9"><p>grid_9</p></div><!-- end .grid_9 --><div class="clear"></div><div class="grid_4"><p>grid_4</p></div><!-- end .grid_4 --><div class="grid_8"><p>grid_8</p></div><!-- end .grid_8 --><div class="clear"></div><div class="grid_5"><p>grid_5</p></div><!-- end .grid_5 --><div class="grid_7"><p>grid_7</p></div><!-- end .grid_7 --><div class="clear"></div><div class="grid_6"><p>grid_6</p></div><!-- end .grid_6 --><div class="grid_6"><p>grid_6</p></div><!-- end .grid_6 --><div class="clear"></div><div class="grid_7"><p>grid_7</p></div><!-- end .grid_7 --><div class="grid_5"><p>grid_5</p></div><!-- end .grid_5 --><div class="clear"></div><div class="grid_8"><p>grid_8</p></div><!-- end .grid_8 --><div class="grid_4"><p>grid_4</p></div><!-- end .grid_4 --><div class="clear"></div><div class="grid_9"><p>grid_9</p></div><!-- end .grid_9 --><div class="grid_3"><p>grid_3</p></div><!-- end .grid_3 --><div class="clear"></div><div class="grid_10"><p>grid_10</p></div><!-- end .grid_10 --><div class="grid_2"><p>grid_2</p></div><!-- end .grid_2 --><div class="clear"></div><div class="grid_11"><p>grid_11</p></div><!-- end .grid_11 --><div class="grid_1"><p>grid_1</p></div><!-- end .grid_1 --><div class="clear"></div></div>

<h1>Typography</h1>

<div class="bs-docs-section">
    <!-- Headings -->
    <h2 id="type-headings">Headings</h2>
    <p>All HTML headings, <code>&lt;h1&gt;</code> through <code>&lt;h6&gt;</code> are available.</p>

    <div class="bs-example bs-example-type">
      <table class="table">
        <tbody>
          <tr>
            <th><h1>Bootstrap heading</h1></th>
            <td>Semibold 38px</td>
          </tr>
          <tr>
            <th><h2>Bootstrap heading</h2></th>
            <td>Semibold 32px</td>
          </tr>
          <tr>
            <th><h3>Bootstrap heading</h3></th>
            <td>Semibold 24px</td>
          </tr>
          <tr>
            <th><h4>Bootstrap heading</h4></th>
            <td>Semibold 18px</td>
          </tr>
          <tr>
            <th><h5>Bootstrap heading</h5></th>
            <td>Semibold 16px</td>
          </tr>
          <tr>
            <th><h6>Bootstrap heading</h6></th>
            <td>Semibold 12px</td>
          </tr>
        </tbody>
      </table>
    </div>
<div class="highlight"><pre><code class="html"><span class="nt">&lt;h1&gt;</span>...<span class="nt">&lt;/h1&gt;</span>
<span class="nt">&lt;h2&gt;</span>...<span class="nt">&lt;/h2&gt;</span>
<span class="nt">&lt;h3&gt;</span>...<span class="nt">&lt;/h3&gt;</span>
<span class="nt">&lt;h4&gt;</span>...<span class="nt">&lt;/h4&gt;</span>
<span class="nt">&lt;h5&gt;</span>...<span class="nt">&lt;/h5&gt;</span>
<span class="nt">&lt;h6&gt;</span>...<span class="nt">&lt;/h6&gt;</span>
</code></pre></div>

    <!-- Body copy -->
    <h2 id="type-body-copy">Body copy</h2>
    <p>Bootstrap's global default <code>font-size</code> is <strong>14px</strong>, with a <code>line-height</code> of <strong>1.428</strong>. This is applied to the <code>&lt;body&gt;</code> and all paragraphs. In addition, <code>&lt;p&gt;</code> (paragraphs) receive a bottom margin of half their computed line-height (10px by default).</p>
    <div class="bs-example">
      <p>Nullam quis risus eget urna mollis ornare vel eu leo. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Nullam id dolor id nibh ultricies vehicula.</p>
      <p>Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Donec ullamcorper nulla non metus auctor fringilla. Duis mollis, est non commodo luctus, nisi erat porttitor ligula, eget lacinia odio sem nec elit. Donec ullamcorper nulla non metus auctor fringilla.</p>
      <p>Maecenas sed diam eget risus varius blandit sit amet non magna. Donec id elit non mi porta gravida at eget metus. Duis mollis, est non commodo luctus, nisi erat porttitor ligula, eget lacinia odio sem nec elit.</p>
    </div>
<div class="highlight"><pre><code class="html"><span class="nt">&lt;p&gt;</span>...<span class="nt">&lt;/p&gt;</span>
</code></pre></div>

    <!-- Body copy .lead -->
    <h3>Lead body copy</h3>
    <p>Make a paragraph stand out by adding <code>.lead</code>.</p>
    <div class="bs-example">
      <p class="lead">Vivamus sagittis lacus vel augue laoreet rutrum faucibus dolor auctor. Duis mollis, est non commodo luctus.</p>
    </div>
<div class="highlight"><pre><code class="html"><span class="nt">&lt;p</span> <span class="na">class=</span><span class="s">"lead"</span><span class="nt">&gt;</span>...<span class="nt">&lt;/p&gt;</span>
</code></pre></div>

    <!-- Using LESS -->
    <h3>Built with Less</h3>
    <p>The typographic scale is based on two LESS variables in <strong>variables.less</strong>: <code>@font-size-base</code> and <code>@line-height-base</code>. The first is the base font-size used throughout and the second is the base line-height. We use those variables and some simple math to create the margins, paddings, and line-heights of all our type and more. Customize them and Bootstrap adapts.</p>


    <!-- Emphasis -->
    <h2 id="type-emphasis">Emphasis</h2>
    <p>Make use of HTML's default emphasis tags with lightweight styles.</p>

    <h3>Small text</h3>
    <p>For de-emphasizing inline or blocks of text, use the <code>&lt;small&gt;</code> tag to set text at 85% the size of the parent. Heading elements receive their own <code>font-size</code> for nested <code>&lt;small&gt;</code> elements.</p>
    <div class="bs-example">
      <p><small>This line of text is meant to be treated as fine print.</small></p>
    </div>
<div class="highlight"><pre><code class="html"><span class="nt">&lt;small&gt;</span>This line of text is meant to be treated as fine print.<span class="nt">&lt;/small&gt;</span>
</code></pre></div>


    <h3>Bold</h3>
    <p>For emphasizing a snippet of text with a heavier font-weight.</p>
    <div class="bs-example">
      <p>The following snippet of text is <strong>rendered as bold text</strong>.</p>
    </div>
<div class="highlight"><pre><code class="html"><span class="nt">&lt;strong&gt;</span>rendered as bold text<span class="nt">&lt;/strong&gt;</span>
</code></pre></div>

    <h3>Italics</h3>
    <p>For emphasizing a snippet of text with italics.</p>
    <div class="bs-example">
      <p>The following snippet of text is <em>rendered as italicized text</em>.</p>
    </div>
<div class="highlight"><pre><code class="html"><span class="nt">&lt;em&gt;</span>rendered as italicized text<span class="nt">&lt;/em&gt;</span>
</code></pre></div>

    <div class="bs-callout bs-callout-info">
      <h4>Alternate elements</h4>
      <p>Feel free to use <code>&lt;b&gt;</code> and <code>&lt;i&gt;</code> in HTML5. <code>&lt;b&gt;</code> is meant to highlight words or phrases without conveying additional importance while <code>&lt;i&gt;</code> is mostly for voice, technical terms, etc.</p>
    </div>

    <h3>Alignment classes</h3>
    <p>Easily realign text to components with text alignment classes.</p>
    <div class="bs-example">
      <p class="text-left">Left aligned text.</p>
      <p class="text-center">Center aligned text.</p>
      <p class="text-right">Right aligned text.</p>
    </div>
<div class="highlight"><pre><code class="html"><span class="nt">&lt;p</span> <span class="na">class=</span><span class="s">"text-left"</span><span class="nt">&gt;</span>Left aligned text.<span class="nt">&lt;/p&gt;</span>
<span class="nt">&lt;p</span> <span class="na">class=</span><span class="s">"text-center"</span><span class="nt">&gt;</span>Center aligned text.<span class="nt">&lt;/p&gt;</span>
<span class="nt">&lt;p</span> <span class="na">class=</span><span class="s">"text-right"</span><span class="nt">&gt;</span>Right aligned text.<span class="nt">&lt;/p&gt;</span>
</code></pre></div>

    <h3>Emphasis classes</h3>
    <p>Convey meaning through color with a handful of emphasis utility classes.</p>
    <div class="bs-example">
      <p class="text-muted">Fusce dapibus, tellus ac cursus commodo, tortor mauris nibh.</p>
      <p class="text-primary">Nullam id dolor id nibh ultricies vehicula ut id elit.</p>
      <p class="text-warning">Etiam porta sem malesuada magna mollis euismod.</p>
      <p class="text-danger">Donec ullamcorper nulla non metus auctor fringilla.</p>
      <p class="text-success">Duis mollis, est non commodo luctus, nisi erat porttitor ligula.</p>
      <p class="text-info">Maecenas sed diam eget risus varius blandit sit amet non magna.</p>
    </div>
<div class="highlight"><pre><code class="html"><span class="nt">&lt;p</span> <span class="na">class=</span><span class="s">"text-muted"</span><span class="nt">&gt;</span>...<span class="nt">&lt;/p&gt;</span>
<span class="nt">&lt;p</span> <span class="na">class=</span><span class="s">"text-primary"</span><span class="nt">&gt;</span>...<span class="nt">&lt;/p&gt;</span>
<span class="nt">&lt;p</span> <span class="na">class=</span><span class="s">"text-warning"</span><span class="nt">&gt;</span>...<span class="nt">&lt;/p&gt;</span>
<span class="nt">&lt;p</span> <span class="na">class=</span><span class="s">"text-danger"</span><span class="nt">&gt;</span>...<span class="nt">&lt;/p&gt;</span>
<span class="nt">&lt;p</span> <span class="na">class=</span><span class="s">"text-success"</span><span class="nt">&gt;</span>...<span class="nt">&lt;/p&gt;</span>
<span class="nt">&lt;p</span> <span class="na">class=</span><span class="s">"text-info"</span><span class="nt">&gt;</span>...<span class="nt">&lt;/p&gt;</span>
</code></pre></div>


    <!-- Abbreviations -->
    <h2 id="type-abbreviations">Abbreviations</h2>
    <p>Stylized implementation of HTML's <code>&lt;abbr&gt;</code> element for abbreviations and acronyms to show the expanded version on hover. Abbreviations with a <code>title</code> attribute have a light dotted bottom border and a help cursor on hover, providing additional context on hover.</p>

    <h3>Basic abbreviation</h3>
    <p>For expanded text on long hover of an abbreviation, include the <code>title</code> attribute with the <code>&lt;abbr&gt;</code> element.</p>
    <div class="bs-example">
      <p>An abbreviation of the word attribute is <abbr title="attribute">attr</abbr>.</p>
    </div>
<div class="highlight"><pre><code class="html"><span class="nt">&lt;abbr</span> <span class="na">title=</span><span class="s">"attribute"</span><span class="nt">&gt;</span>attr<span class="nt">&lt;/abbr&gt;</span>
</code></pre></div>

    <h3>Initialism</h3>
    <p>Add <code>.initialism</code> to an abbreviation for a slightly smaller font-size.</p>
    <div class="bs-example">
      <p><abbr title="HyperText Markup Language" class="initialism">HTML</abbr> is the best thing since sliced bread.</p>
    </div>
<div class="highlight"><pre><code class="html"><span class="nt">&lt;abbr</span> <span class="na">title=</span><span class="s">"HyperText Markup Language"</span> <span class="na">class=</span><span class="s">"initialism"</span><span class="nt">&gt;</span>HTML<span class="nt">&lt;/abbr&gt;</span>
</code></pre></div>


    <!-- Addresses -->
    <h2 id="type-addresses">Addresses</h2>
    <p>Present contact information for the nearest ancestor or the entire body of work. Preserve formatting by ending all lines with <code>&lt;br&gt;</code>.</p>
    <div class="bs-example">
      <address>
        <strong>Twitter, Inc.</strong><br>
        795 Folsom Ave, Suite 600<br>
        San Francisco, CA 94107<br>
        <abbr title="Phone">P:</abbr> (123) 456-7890
      </address>
      <address>
        <strong>Full Name</strong><br>
        <a href="mailto:#">first.last@example.com</a>
      </address>
    </div>
<div class="highlight"><pre><code class="html"><span class="nt">&lt;address&gt;</span>
  <span class="nt">&lt;strong&gt;</span>Twitter, Inc.<span class="nt">&lt;/strong&gt;&lt;br&gt;</span>
  795 Folsom Ave, Suite 600<span class="nt">&lt;br&gt;</span>
  San Francisco, CA 94107<span class="nt">&lt;br&gt;</span>
  <span class="nt">&lt;abbr</span> <span class="na">title=</span><span class="s">"Phone"</span><span class="nt">&gt;</span>P:<span class="nt">&lt;/abbr&gt;</span> (123) 456-7890
<span class="nt">&lt;/address&gt;</span>

<span class="nt">&lt;address&gt;</span>
  <span class="nt">&lt;strong&gt;</span>Full Name<span class="nt">&lt;/strong&gt;&lt;br&gt;</span>
  <span class="nt">&lt;a</span> <span class="na">href=</span><span class="s">"mailto:#"</span><span class="nt">&gt;</span>first.last@example.com<span class="nt">&lt;/a&gt;</span>
<span class="nt">&lt;/address&gt;</span>
</code></pre></div>


    <!-- Blockquotes -->
    <h2 id="type-blockquotes">Blockquotes</h2>
    <p>For quoting blocks of content from another source within your document.</p>

    <h3>Default blockquote</h3>
    <p>Wrap <code>&lt;blockquote&gt;</code> around any <abbr title="HyperText Markup Language">HTML</abbr> as the quote. For straight quotes, we recommend a <code>&lt;p&gt;</code>.</p>
    <div class="bs-example">
      <blockquote>
        <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer posuere erat a ante.</p>
      </blockquote>
    </div>
<div class="highlight"><pre><code class="html"><span class="nt">&lt;blockquote&gt;</span>
  <span class="nt">&lt;p&gt;</span>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer posuere erat a ante.<span class="nt">&lt;/p&gt;</span>
<span class="nt">&lt;/blockquote&gt;</span>
</code></pre></div>

    <h3>Blockquote options</h3>
    <p>Style and content changes for simple variations on a standard <code>&lt;blockquote&gt;</code>.</p>

    <h4>Naming a source</h4>
    <p>Add <code>&lt;small&gt;</code> tag for identifying the source. Wrap the name of the source work in <code>&lt;cite&gt;</code>.</p>
    <div class="bs-example">
      <blockquote>
        <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer posuere erat a ante.</p>
        <small>Someone famous in <cite title="Source Title">Source Title</cite></small>
      </blockquote>
    </div>
<div class="highlight"><pre><code class="html"><span class="nt">&lt;blockquote&gt;</span>
  <span class="nt">&lt;p&gt;</span>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer posuere erat a ante.<span class="nt">&lt;/p&gt;</span>
  <span class="nt">&lt;small&gt;</span>Someone famous in <span class="nt">&lt;cite</span> <span class="na">title=</span><span class="s">"Source Title"</span><span class="nt">&gt;</span>Source Title<span class="nt">&lt;/cite&gt;&lt;/small&gt;</span>
<span class="nt">&lt;/blockquote&gt;</span>
</code></pre></div>

    <h4>Alternate displays</h4>
    <p>Use <code>.pull-right</code> for a floated, right-aligned blockquote.</p>
    <div class="bs-example" style="overflow: hidden;">
      <blockquote class="pull-right">
        <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer posuere erat a ante.</p>
        <small>Someone famous in <cite title="Source Title">Source Title</cite></small>
      </blockquote>
    </div>
<div class="highlight"><pre><code class="html"><span class="nt">&lt;blockquote</span> <span class="na">class=</span><span class="s">"pull-right"</span><span class="nt">&gt;</span>
  ...
<span class="nt">&lt;/blockquote&gt;</span>
</code></pre></div>


    <!-- Lists -->
    <h2 id="type-lists">Lists</h2>

    <h3>Unordered</h3>
    <p>A list of items in which the order does <em>not</em> explicitly matter.</p>
    <div class="bs-example">
      <ul>
        <li>Lorem ipsum dolor sit amet</li>
        <li>Consectetur adipiscing elit</li>
        <li>Integer molestie lorem at massa</li>
        <li>Facilisis in pretium nisl aliquet</li>
        <li>Nulla volutpat aliquam velit
          <ul>
            <li>Phasellus iaculis neque</li>
            <li>Purus sodales ultricies</li>
            <li>Vestibulum laoreet porttitor sem</li>
            <li>Ac tristique libero volutpat at</li>
          </ul>
        </li>
        <li>Faucibus porta lacus fringilla vel</li>
        <li>Aenean sit amet erat nunc</li>
        <li>Eget porttitor lorem</li>
      </ul>
    </div>
<div class="highlight"><pre><code class="html"><span class="nt">&lt;ul&gt;</span>
  <span class="nt">&lt;li&gt;</span>...<span class="nt">&lt;/li&gt;</span>
<span class="nt">&lt;/ul&gt;</span>
</code></pre></div>

    <h3>Ordered</h3>
    <p>A list of items in which the order <em>does</em> explicitly matter.</p>
    <div class="bs-example">
      <ol>
        <li>Lorem ipsum dolor sit amet</li>
        <li>Consectetur adipiscing elit</li>
        <li>Integer molestie lorem at massa</li>
        <li>Facilisis in pretium nisl aliquet</li>
        <li>Nulla volutpat aliquam velit</li>
        <li>Faucibus porta lacus fringilla vel</li>
        <li>Aenean sit amet erat nunc</li>
        <li>Eget porttitor lorem</li>
      </ol>
    </div>
<div class="highlight"><pre><code class="html"><span class="nt">&lt;ol&gt;</span>
  <span class="nt">&lt;li&gt;</span>...<span class="nt">&lt;/li&gt;</span>
<span class="nt">&lt;/ol&gt;</span>
</code></pre></div>

    <h3>Unstyled</h3>
    <p>Remove the default <code>list-style</code> and left margin on list items (immediate children only). <strong>This only applies to immediate children list items</strong>, meaning you will need to add the class for any nested lists as well.</p>
    <div class="bs-example">
      <ul class="list-unstyled">
        <li>Lorem ipsum dolor sit amet</li>
        <li>Consectetur adipiscing elit</li>
        <li>Integer molestie lorem at massa</li>
        <li>Facilisis in pretium nisl aliquet</li>
        <li>Nulla volutpat aliquam velit
          <ul>
            <li>Phasellus iaculis neque</li>
            <li>Purus sodales ultricies</li>
            <li>Vestibulum laoreet porttitor sem</li>
            <li>Ac tristique libero volutpat at</li>
          </ul>
        </li>
        <li>Faucibus porta lacus fringilla vel</li>
        <li>Aenean sit amet erat nunc</li>
        <li>Eget porttitor lorem</li>
      </ul>
    </div>
<div class="highlight"><pre><code class="html"><span class="nt">&lt;ul</span> <span class="na">class=</span><span class="s">"list-unstyled"</span><span class="nt">&gt;</span>
  <span class="nt">&lt;li&gt;</span>...<span class="nt">&lt;/li&gt;</span>
<span class="nt">&lt;/ul&gt;</span>
</code></pre></div>

    <h3>Inline</h3>
    <p>Place all list items on a single line with <code>inline-block</code> and some light padding.</p>
    <div class="bs-example">
      <ul class="list-inline">
        <li>Lorem ipsum</li>
        <li>Phasellus iaculis</li>
        <li>Nulla volutpat</li>
      </ul>
    </div>
<div class="highlight"><pre><code class="html"><span class="nt">&lt;ul</span> <span class="na">class=</span><span class="s">"list-inline"</span><span class="nt">&gt;</span>
  <span class="nt">&lt;li&gt;</span>...<span class="nt">&lt;/li&gt;</span>
<span class="nt">&lt;/ul&gt;</span>
</code></pre></div>

    <h3>Description</h3>
    <p>A list of terms with their associated descriptions.</p>
    <div class="bs-example">
      <dl>
        <dt>Description lists</dt>
        <dd>A description list is perfect for defining terms.</dd>
        <dt>Euismod</dt>
        <dd>Vestibulum id ligula porta felis euismod semper eget lacinia odio sem nec elit.</dd>
        <dd>Donec id elit non mi porta gravida at eget metus.</dd>
        <dt>Malesuada porta</dt>
        <dd>Etiam porta sem malesuada magna mollis euismod.</dd>
      </dl>
    </div>
<div class="highlight"><pre><code class="html"><span class="nt">&lt;dl&gt;</span>
  <span class="nt">&lt;dt&gt;</span>...<span class="nt">&lt;/dt&gt;</span>
  <span class="nt">&lt;dd&gt;</span>...<span class="nt">&lt;/dd&gt;</span>
<span class="nt">&lt;/dl&gt;</span>
</code></pre></div>

    <h4>Horizontal description</h4>
    <p>Make terms and descriptions in <code>&lt;dl&gt;</code> line up side-by-side.</p>
    <div class="bs-example">
      <dl class="dl-horizontal">
        <dt>Description lists</dt>
        <dd>A description list is perfect for defining terms.</dd>
        <dt>Euismod</dt>
        <dd>Vestibulum id ligula porta felis euismod semper eget lacinia odio sem nec elit.</dd>
        <dd>Donec id elit non mi porta gravida at eget metus.</dd>
        <dt>Malesuada porta</dt>
        <dd>Etiam porta sem malesuada magna mollis euismod.</dd>
        <dt>Felis euismod semper eget lacinia</dt>
        <dd>Fusce dapibus, tellus ac cursus commodo, tortor mauris condimentum nibh, ut fermentum massa justo sit amet risus.</dd>
      </dl>
    </div>
<div class="highlight"><pre><code class="html"><span class="nt">&lt;dl</span> <span class="na">class=</span><span class="s">"dl-horizontal"</span><span class="nt">&gt;</span>
  <span class="nt">&lt;dt&gt;</span>...<span class="nt">&lt;/dt&gt;</span>
  <span class="nt">&lt;dd&gt;</span>...<span class="nt">&lt;/dd&gt;</span>
<span class="nt">&lt;/dl&gt;</span>
</code></pre></div>

    <div class="bs-callout bs-callout-info">
      <h4>Auto-truncating</h4>
      <p>Horizontal description lists will truncate terms that are too long to fit in the left column with <code>text-overflow</code>. In narrower viewports, they will change to the default stacked layout.</p>
    </div>
  </div>
  {% endblock %}