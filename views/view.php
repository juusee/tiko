<?php

class View {

    function tyo($data, $kaytetyt_tarvikkeet, $tehdyt_tyot, $tarvikkeet, $tyot) {
        ?>
        <div class="row">
            <table class="table table-bordered">
                <tr>
                    <th>Työsuoritusnumero</th>
                    <th>Tila</th>
                    <th>Hinta</th>
                    <th>Kaupunki</th>
                    <th>Postinumero</th>
                    <th>Katuosoite</th>
                    <th>Tyyppi</th>
                    <th>Aiempi työ</th>
                    <th>Asiakasnro</th>
                </tr>
                <?php
                    echo '<tr>';
                    foreach($data as $col) {
                        echo '<td>' . $col . '</td>';
                    }
                    echo '</tr>';
                ?>
            </table>

            <h3>Käytetyt tarvikkeet</h3>
            <table class="table table-bordered">
                <tr>
                    <th>Nimi</th>
                    <th>Määrä</th>
                    <th>Alennus</th>
                </tr>
                <?php
                foreach ($kaytetyt_tarvikkeet as $kaytetty_tarvike) {
                    echo '<tr>';
                    foreach ($tarvikkeet as $tarvike) {
                        if ($tarvike[0] == $kaytetty_tarvike[0]) {
                            echo '<td>' . $tarvike[1] . '</td>';
                            break;
                        }
                    }
                    echo '<td>' . $kaytetty_tarvike[2] . '</td>';
                    echo '<td>' . $kaytetty_tarvike[3] . '</td>';
                    echo '</tr>';
                }
                ?>
            </table>

            <h3>Tehty työ</h3>
            <table class="table table-bordered">
                <tr>
                    <th>Nimi</th>
                    <th>Tunnit</th>
                    <th>Alennus</th>
                </tr>
                <?php
                foreach ($tehdyt_tyot as $tehty_tyo) {
                    echo '<tr>';
                    echo '<td>' . $tehty_tyo[1] . '</td>';
                    echo '<td>' . $tehty_tyo[2] . '</td>';
                    echo '<td>' . $tehty_tyo[3] . '</td>';
                    echo '</tr>';
                }
                ?>
            </table>
        </div>


        <div class="row">
            <div class="col-md-6">
                <h3>Lisää työtä</h3>
                <form action="/tiko2/lisaaTyo" method="post">
                    <?php
                    $end = END_URI;
                    echo "<input type='hidden' value='{$end}' name='tyo[tsnro]'>";
                    ?>
                    <div class="form-group">
                        <select class="form-control" id="tyo" name="tyo[nimi]">
                            <?php
                            foreach ($tyot as $tyo) {
                                echo "<option value='{$tyo[0]}'>{$tyo[0]}</option>";
                            }
                            ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="tunnit">Tunnit</label>
                        <input type="number" min="0" step="0.5" id="tunnit" name="tyo[tunnit]"">
                    </div>
                    <div class="form-group">
                        <label for="alennus">Alennus</label>
                        <input type="number" min="0" step="1" id="alennus" name="tyo[alennus]">
                    </div>
                    <button type="submit" class="btn btn-default">Lisää työ</button>
                </form>
            </div>

            <div class="col-md-6">
                <form action="/tiko2/lisaaTarvike" method="post">
                    <?php
                    $end = END_URI;
                    echo "<input type='hidden' value='{$end}' name='tarvike[tsnro]'>";
                    ?>
                    <h3>Lisää tarvikkeita</h3>
                    <div class="form-group">
                        <select class="form-control" id="tarvike" name="tarvike[trnro]">
                            <?php
                                foreach ($tarvikkeet as $tarvike) {
                                    echo "<option value='{$tarvike[0]}'>{$tarvike[1]} (jäljellä: {$tarvike[4]})</option>";
                                }
                            ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="maara">Määrä</label>
                        <input type="number" min="0" step="0.1" id="maara" name="tarvike[maara]">
                    </div>
                    <div class="form-group">
                        <label for="alennus">Alennus</label>
                        <input type="number" min="0" step="1" id="alennus" name="tarvike[alennus]">
                    </div>
                    <button type="submit" class="btn btn-default">Lisää tarvike</button>
                </form>
            </div>
        </div>
        <?php
    }

	function tyot($data) {
		?>
        <table class="table table-bordered">
            <tr>
                <th>Työ nro</th>
                <th>Tila</th>
                <th>Hinta</th>
                <th>Kaupunki</th>
                <th>Postinumero</th>
                <th>Katuosoite</th>
                <th>Tyyppi</th>
                <th>Aiempi työ</th>
                <th>Asiakasnro</th>
            </tr>
        <?php
            foreach ($data as $row) {
              echo '<tr>';
              foreach($row as $col) {
                  echo '<td>' . $col . '</td>';
              }
              echo "<td><a href='/tiko2/tyot/{$row[0]}'><button class='btn btn-info'>Siirry työhön</button></a></td>";
              echo '</tr>';
          }
        ?>
        </table>
        <?php
	}

    function tyokohde() {
        ?>
        <form action="luoTyokohde" method="post">
            <div class="form-group">
                <label for="kaupunki">Kaupunki</label>
                <input type="text" class="form-control" id="kaupunki" name="tk[kaupunki]">
            </div>
            <div class="form-group">
                <label for="postinumero">Postinumero</label>
                <input type="text" class="form-control" id="postinumero" name="tk[postinumero]">
            </div>
            <div class="form-group">
                <label for="katuosoite">Katuosoite</label>
                <input type="text" class="form-control" id="katuosoite" name="tk[katuosoite]">
            </div>
            <div class="form-group">
                <label for="tyyppi">Tyyppi:</label>
                <select class="form-control" id="tyyppi" name="tk[tyyppi]">
                    <option value="omakotitalo">Omakotitalo</option>
                    <option value="kerrostalo">Kerrostalo</option>
                </select>
            </div>
            <div class="form-group">
                <label for="anro">Asiakasnumero</label>
                <input type="text" class="form-control" id="anro" name="tk[anro]">
            </div>
            <button type="submit" class="btn btn-default">Luo urakkatarjous</button>
        </form>
        <?php
    }
}