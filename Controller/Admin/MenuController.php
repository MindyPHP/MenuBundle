<?php

declare(strict_types=1);

/*
 * This file is part of Mindy Framework.
 * (c) 2018 Maxim Falaleev
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Mindy\Bundle\MenuBundle\Controller\Admin;

use Mindy\Bundle\AdminBundle\Sort\SortFactory;
use Mindy\Bundle\MenuBundle\Form\Admin\MenuForm;
use Mindy\Bundle\MenuBundle\Model\Menu;
use Mindy\Bundle\MindyBundle\Controller\Controller;
use Mindy\Bundle\PaginationBundle\Utils\PaginationTrait;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class MenuController extends Controller
{
    use PaginationTrait;

    public function list(Request $request, int $parent_id = null)
    {
        $parent = null;
        $qs = Menu::objects()->order(['root', 'lft']);
        if (empty($parent_id)) {
            $qs->roots();
        } else {
            $parent = Menu::objects()->get(['id' => $parent_id]);
            if (null === $parent) {
                throw new NotFoundHttpException();
            }

            $qs->filter(['parent_id' => $parent_id]);
        }

        $sort = $request->request->get('sort', []);
        if (false === empty($sort) || false === is_array($sort)) {
            /** @var SortFactory $sortHandler */
            $sortHandler = $this->get(SortFactory::class);
            $sortHandler->sort($qs, $sort);
        }

        $pager = $this->createPagination($qs);

        return $this->render('admin/menu/menu/list.html', [
            'menu' => $pager->paginate(),
            'parent' => $parent,
            'pager' => $pager->createView(),
        ]);
    }

    public function create(Request $request, int $parent_id = null)
    {
        $parent = null;
        if (false === empty($parent_id)) {
            $parent = Menu::objects()->get(['id' => $parent_id]);
            if (null === $parent) {
                throw new NotFoundHttpException();
            }
        }

        $menu = new Menu([
            'parent' => $parent
        ]);

        $form = $this->createForm(MenuForm::class, $menu, [
            'method' => 'POST',
            'action' => $this->generateUrl('admin_menu_create'),
        ]);

        if ($form->handleRequest($request) && $form->isValid()) {
            $menu = $form->getData();
            if (false === $menu->save()) {
                throw new \RuntimeException('Error while save menu');
            }

            $this->addFlash('success', 'Меню успешно сохранено');

            return $this->redirectToRoute('admin_menu_list');
        }

        return $this->render('admin/menu/menu/create.html', [
            'form' => $form->createView(),
            'parent' => $parent,
        ]);
    }

    public function update(Request $request, $id)
    {
        $menu = Menu::objects()->get(['id' => $id]);
        if (null === $menu) {
            throw new NotFoundHttpException();
        }

        $form = $this->createForm(MenuForm::class, $menu, [
            'method' => 'POST',
            'action' => $this->generateUrl('admin_menu_update', ['id' => $id]),
        ]);

        if ($form->handleRequest($request) && $form->isValid()) {
            $menu = $form->getData();
            if (false === $menu->save()) {
                throw new \RuntimeException('Error while save menu');
            }

            $this->addFlash('success', 'Меню успешно сохранено');

            return $this->redirectToRoute('admin_menu_list');
        }

        return $this->render('admin/menu/menu/update.html', [
            'form' => $form->createView(),
            'menu' => $menu,
        ]);
    }

    public function remove(Request $request, $id)
    {
        $menu = Menu::objects()->get(['id' => $id]);
        if (null === $menu) {
            throw new NotFoundHttpException();
        }

        $menu->delete();

        $this->addFlash('success', 'Меню успешно удалено');

        return $this->redirectToRoute('admin_menu_list');
    }
}
