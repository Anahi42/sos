<?php
$estiloPagina = 'adocao.css';
require_once 'header.php';
?>
    <form action="#" class="container-sos formulario-pesquisa-animais">
        <h2>Conheça nossos animais!</h2>
        <select name="animais" id="animais">
            <option value="">--Selecione--</option>
            <?php
            $animais = get_terms(array('taxonomy' => 'animais'));
            foreach ($animais as $animal):?>
                <option value="<?= $animal->name ?>"
                <?= !empty($_GET['animais']) && $_GET['animais'] === $animal->name ? 'selected' : '' ?>><?= $animal->name ?></option>
            <?php endforeach;
            ?>
        </select>
        <input type="submit" value="Pesquisar">
    </form>
<?php



if(!empty($_GET['animais'])) {
    $animalSelecionado = array(array(
        'taxonomy' => 'animais',
        'field' => 'name',
        'terms' => $_GET['animais']
    ));
}

$args = array(
    'post_type' => 'adocao',
    'tax_query' => !empty($_GET['animais']) ? $animalSelecionado : ''
);
$query = new WP_Query($args);
if ($query->have_posts()):
    echo '<main class="adocao">';
    echo '<ul class="lista-adocao container-sos">';
    while ($query->have_posts()): $query->the_post();

        echo '<li class="col-md-3 adocao" >';
        the_post_thumbnail();
        the_title('<p class="titulo-adocao">', '</p>');
        the_content();
        echo '</li>';
    endwhile;
    echo '</ul>';
    echo '</main>';
    
endif;
?>
<?php the_content();
require_once 'footer.php'; ?>