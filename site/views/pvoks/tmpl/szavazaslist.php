<div id="szavazaslist" class="szavazaslist">
  <h3><a href="<?php echo JURI::base(); ?>indexx.php?option=com_content&view=category&id=<?php echo $this->temakor->id; ?>">
       <?php echo $this->temakor->title; ?>
    </a>
  </h3>
  <h2>Szavazások listája</h2>
  <p>A szavazás cimére kattintva az alternatívák istájához jut, itt lesz lehetősége - bejelentkezés után - szavazni is (ha még nem szavazott).</p>
  <?php foreach ($this->items as $item) : ?>
    <p>
	  <a href="<?php echo JURI::base(); ?>index.php?option=com_content&view=category&layout=articles&id=<?php echo $item->id?>">
	    <?php echo $item->title; ?>
	  </a>
	</p>
  <?php endforeach; ?>
</div>