<?php

namespace AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Symfony\Component\EventDispatcher\GenericEvent;
use AdminBundle\Controller\TokenAuthenticatedController;
use AdminBundle\Repository\CRUDDecorator;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use AdminBundle\Form\Type\FilterFormType;

use AdminBundle\Model\Sort;
use AdminBundle\Model\Filters;

use AdminBundle\CRUDEvents;


class CRUDController extends CRUDBaseController implements TokenAuthenticatedController
{

    public function createAction(Request $request)
    {
        $item = new $this->model_type;
        $this->denyAccessUnlessGranted('create', $item, 'You cannot create this item.' );
        $this->checkRoutePermission($request);

        $form = $this->getFormFactory()->create( $this->form_type, $item );

        try {
            if ($this->form_handler->handle($request,$form)) {
                $this->addFlash( 'success', 'admin.crud.flash.success' );
                return $this->redirect( $this->generateUrl('_app_crud_%s_edit', array('id' => $item->getId() )));
            }            
        } catch (\Exception $e) {
            $this->addFlash( 'danger', $e->getMessage() );
        }

        return $this->render($this->getEditView(), array(
            'item' => $item,
            'form' => $form->createView(),
            'route_pattern' => $this->route_pattern,
            'form_view' => $this->getFormView(),
        ));
    }

    public function editAction(Request $request, $id )
    {
        $this->request = $request;
        $decorator = new CRUDDecorator($this->getRepository());
        $item = $decorator->getEditItem($id);
        

        $this->denyAccessUnlessGranted('view', $item, 'You cannot edit this item.' );
        $this->checkRoutePermission($request);

        $form = $this->getFormFactory()->create( $this->form_type, $item );

        try {
            if ($this->form_handler->handle($request,$form)) {
                $this->addFlash( 'success', 'admin.crud.flash.success' );
                $queryParams = $request->query->all();
                $queryParams = array_merge($queryParams,array('id' => $id ));
                return $this->redirect( $this->generateUrl('_app_crud_%s_edit', $queryParams ));
            }            
        } catch (\Exception $e) {
            $this->addFlash( 'danger', $e->getMessage() );
        }

        $params = array(
            'item' => $item,
            'form' => $form->createView(),
            'route_pattern' => $this->route_pattern,
            'form_view' => $this->getFormView(),
        );

        $event = new GenericEvent($params);
        $this->getEventDispatcher()->dispatch(CRUDEvents::EDIT, $event);

        return $this->render($this->getEditView(), $params );
    }

    public function listAction(Request $request)
    {
        $this->checkRoutePermission($request);

        $decorator = new CRUDDecorator($this->getRepository());

        $sort = new Sort($request->query->all());
        $filters = new Filters($request->query->get('filters',array()));
        $decorator->setSort( $sort );
        $decorator->setFilters($filters);
        $decorator->setFilterBag($this->filter_bag);
        $decorator->setSearchFields($this->search_fields);

        $items = $decorator->getList();

        $form_filters = $this->getFormFactory()->create( FilterFormType::class, $filters );

        $view = 'AdminBundle:crud:list.html.twig';
        if (isset($this->templates['list'])) {
            $view = $this->templates['list'];
        }

        $params = array(
            'items' => $items,
            'route_pattern' => $this->route_pattern,
            'form_filters' => $form_filters->createView(),
            'list_fields' => $this->list_fields,
            'list_actions' => $this->list_actions,
            'export_fields' => $this->export_fields,
            'metadata' => $this->getMetadata(),
        );

        return $this->render($view, $params);
    }

    public function removeAction(Request $request, $id)
    {
        try {
            $item = $this->getRepository()->find($id);
            $this->denyAccessUnlessGranted('delete', $item, 'You cannot delete this item.' );
            $this->checkRoutePermission($request);

            $em = $this->doctrine;
            $em->remove($item);
            $em->flush();

            $this->addFlash( 'success', 'admin.crud.flash.removed' );
            
            $url = $request->query->has('redirect') ? $request->query->get('redirect') : $this->generateUrl('_app_crud_%s_list');

            return $this->redirect($url);

        } catch (\Exception $e) {
            $this->addFlash( 'danger', $e->getMessage() );
            return $this->redirect( $this->generateUrl('_app_crud_%s_edit', array('id' => $id )));
        }

    }

    public function exportAction(Request $request)
    {
        $model_type = $this->getClassName($this->model_type);
        $this->tableName = $this->doctrine->getClassMetadata('AppBundle:'.$model_type)->getTableName();

        //$response = new Response();
        $response = new StreamedResponse();
        $response->setCallback(function() {
            $handle = fopen('php://output', 'w+');

            fputs($handle, $bom =( chr(0xEF) . chr(0xBB) . chr(0xBF) ));
            
            // Add the header of the CSV file
            fputcsv($handle, $this->export_fields,';');
            // Query data from database

            $fields = array_map("\Doctrine\Common\Util\Inflector::tableize", $this->export_fields);

            $sql = sprintf('SELECT %s FROM %s', implode(',',$fields), $this->tableName );
            $results = $this->doctrine->getConnection()->query( $sql );

            // Add the data queried from database
            while($row = $results->fetch() ) {
                //$line = $this->exporter->handleRow( $this->export_fields, $row );
                fputcsv(
                    $handle, // The file pointer
                    $row, // The fields
                    ';' // The delimiter
                );
            }
            fclose($handle);
        });
        $response->setStatusCode(200);
        $response->headers->set('Content-Type', 'text/csv; charset=utf-8');
        $response->headers->set('Content-Disposition', 'attachment; filename="export_'.$this->route_pattern.'.csv"');
        return $response;
    }

}