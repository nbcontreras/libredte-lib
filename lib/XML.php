<?php

/**
 * LibreDTE
 * Copyright (C) SASCO SpA (https://sasco.cl)
 *
 * Este programa es software libre: usted puede redistribuirlo y/o
 * modificarlo bajo los términos de la Licencia Pública General GNU
 * publicada por la Fundación para el Software Libre, ya sea la versión
 * 3 de la Licencia, o (a su elección) cualquier versión posterior de la
 * misma.
 *
 * Este programa se distribuye con la esperanza de que sea útil, pero
 * SIN GARANTÍA ALGUNA; ni siquiera la garantía implícita
 * MERCANTIL o de APTITUD PARA UN PROPÓSITO DETERMINADO.
 * Consulte los detalles de la Licencia Pública General GNU para obtener
 * una información más detallada.
 *
 * Debería haber recibido una copia de la Licencia Pública General GNU
 * junto a este programa.
 * En caso contrario, consulte <http://www.gnu.org/licenses/gpl.html>.
 */

namespace sasco\LibreDTE;

/**
 * Clase para trabajar con XMLs
 * @author Esteban De La Fuente Rubio, DeLaF (esteban[at]sasco.cl)
 * @version 2015-08-05
 */
class XML extends \DomDocument
{

    /**
     * Constructor de la clase XML
     * @param version Versión del documento XML
     * @param encoding Codificación del documento XML
     * @author Esteban De La Fuente Rubio, DeLaF (esteban[at]sasco.cl)
     * @version 2015-08-05
     */
    public function __construct($version = '1.0', $encoding = 'ISO-8859-1')
    {
        parent::__construct($version, $encoding);
        $this->formatOutput = true;
    }

    /**
     * Método que genera nodos XML a partir de un arreglo
     * @param array Arreglo con los datos que se usarán para generar XML
     * @param parent DOMElement padre para los elementos, o =null para que sea la raíz
     * @return DomDocument
     * @author Esteban De La Fuente Rubio, DeLaF (esteban[at]sasco.cl)
     * @version 2015-08-06
     */
    public function generate(array $array, \DOMElement &$parent = null)
    {
        if ($parent===null)
            $parent = &$this;
        foreach ($array as $key => $value) {
            if ($key=='@attributes') {
                foreach ($value as $attr => $val)
                    $parent->setAttribute($attr, $val);
            } else if ($key=='@value') {
                $parent->nodeValue = $value;
            } else {
                if (is_array($value)) {
                    $keys = array_keys($value);
                    if (!is_int($keys[0])) {
                        $value = [$value];
                    }
                    foreach ($value as $value2) {
                        $Node = new \DOMElement($key);
                        $parent->appendChild($Node);
                        $this->generate($value2, $Node);
                    }
                } else {
                    if (is_object($value) and $value instanceof \DOMElement) {
                        $Node = $this->importNode($value, true);
                    } else {
                        $Node = new \DOMElement($key, $value);
                    }
                    $parent->appendChild($Node);
                }
            }
        }
        return $this;
    }

}
