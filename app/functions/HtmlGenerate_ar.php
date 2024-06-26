<?php
namespace functions;

class HtmlGenerate 
{
    public function generateILVProduct($plaque, $category)
    {
        $html = '<div Bclass="d-block d-sm-flex align-items-center pt-4 pb-2 ">';
        $html .= '<div class="d-block mb-3 mb-sm-0 me-sm-4 ms-sm-0 mx-auto"  style="width: 8.5rem;">';
        $html .= '<img class="rounded-4" src="' . $plaque['img_qr_code'] . '" alt="Votre ILV digital" >';
        $html .= '</div>';
        $html .= '<div class="text-center text-sm-start">';
        $html .= '<h3 class="h6 product-title mb-2">Plaque ' . ucwords($category->getCategoryName($plaque['category_id'])['category_name']) . '</h3>';
        $html .= '<div class="d-inline-block text-accent">Plaque N°: ' . $plaque['id'] . '</div>';
        $html .= '<div class="d-inline-block text-muted fs-ms border-start ms-2 ps-2">Catégorie: ' . $category->getCategoryName($plaque['category_id'])['category_name'] . '</div>';

        if ($plaque['active_plate'] == 0) {
            $html .= '<div class="d-inline-block text-muted fs-ms border-start ms-2 ps-2">état: <span class="badge bg-danger me-2 mb-2">Non active</span></div>';
        } else {
            $html .= '<div class="d-inline-block text-muted fs-ms border-start ms-2 ps-2">état: <span class="badge bg-success me-2 mb-2">Active</span></div>';
        }
         $html .= '<div class="text-muted"><small>Référence:</small> ' . $plaque['unique_number'] . '</div>';
        

        if ($plaque['active_plate'] == 0) {
            // Form active plate
            $html .= '<div class=mt-1 mb-3">';
            $html .= '<form action="" class="needs-validation" method="post">';
            $html .= '<label for="link-input" class="form-label">' . 'Entrez' . ($plaque['category_id'] == 5 ? ' votre e-mail récepteur' : ' le nom de votre établissement') . ' pour activé votre ILV ' .  ucwords($category->getCategoryName($plaque['category_id'])['category_name']) . '</label>';
            /* test form link google */
            
            /* end */
            $html .= '<input class="form-control" name="link_plate" type="text" id="'.($plaque['category_id'] == 1 ? 'nomEtablissement' : 'link-input' ).'" placeholder="' . ($plaque['category_id'] == 5 ? 'Entrez votre Email' : 'Exemple: Pizza time paris') . '" required><i class="ci-search"></i>';
                /* liste for autocomplite name etablissment  */
                if($plaque['category_id'] == 1){
                    $html .=  '<ul id="suggestions" class="suggestion list-group mt-2"> </ul>';
                }
                /* end */
                if (!empty($message)) {
                    $html .= '<span>' . $message . '</span>'; 
                }
            $html .= '<div class="form-text">Aide? Où puis-je trouver mon lien ' . ucwords($category->getCategoryName($plaque['category_id'])['category_name']) . ' </div>';
            $html .= '<input class="d-inline btn btn-primary mt-3" type="submit" name="active_plate" value="Activer">';
            $html .= '</form>';
            $html .= '</div>';
            }else{
            $html .= '<div class="d-flex justify-content-center justify-content-sm-start pt-3">';
            $html .= '<button class="btn bg-faded-info btn-icon me-2" type="button" data-bs-toggle="tooltip" title="Modifier le lien de cette ILV" onclick="showUpdateForm(' . $plaque['id'] . ')"><i class="ci-edit text-info"></i></button>';
            $html .= '<button class="btn bg-faded-danger btn-icon me-2" type="button" data-bs-toggle="tooltip" title="Réinitialiser la plaque" name="reset-plate"><i class="ci-compare text-danger"></i></button>';
            $html .= '</div>';
             // Form for updating the link (initially hidden)
            $html .= '<div id="updateForm' . $plaque['id'] . '" style="display:none;">';
            $html .= '<form action="" class="needs-validation" method="post">';
            $html .= '<label for="update-link-input" class="form-label">' . 'Entrez le nouveau ' . ($plaque['category_id'] == 5 ? 'e-mail' : 'nom de votre établissement') . ' pour mettre à jour votre ILV ' .  ucwords($category->getCategoryName($plaque['category_id'])['category_name']) . '</label>';
            $html .= '<input class="form-control widget-filter-search form-control rounded-end" name="update_link" type="text"  id="'.($plaque['category_id'] == 1 ? $plaque['id'].'nomEtablissement' : 'link-input' ).'" placeholder="' . ($plaque['category_id'] == 5 ? 'Entrez votre nouvel Email' : 'Exemple: Pizza time paris') . '" required>';
                /* liste for autocomplite name etablissment  */
                if($plaque['category_id'] == 1){
                    $html .=  '<ul id="'.$plaque['id'].'suggestions" class="suggestion list-group mt-2"> </ul>';
                }
                /* end */
                if (!empty($message)) {
                    $html .= '<span>' . $message . '</span>'; 
                }
            $html .= '<input type="hidden" name="plate_id" value="' . $plaque['id'] . '">';
            $html .= '<input class="d-inline btn btn-info mt-3" type="submit" name="update_plate_link" value="Mettre à jour">';
            $html .= '</form>';
            $html .= '</div>';
        }
        // form
        $html .= '</div>';
        $html .= '</div>';

        return $html;
    }
}
?>
