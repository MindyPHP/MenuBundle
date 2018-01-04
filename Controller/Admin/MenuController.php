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

use Mindy\Bundle\MenuBundle\Form\Admin\MenuForm;
use Mindy\Bundle\MenuBundle\Model\Menu;
use Mindy\Bundle\MindyBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class MenuController extends Controller
{
    public function list(Request $request)
    {
        $menu = Menu::objects()->order(['root', 'lft'])->all();

        return $this->render('admin/menu/menu/list.html', [
            'menu' => $menu,
        ]);
    }

    public function sort(Request $request)
    {
        //        $sortHandler = $this->get('mindy.bundle.admin.utils.sort_handler');
//        $sortHandler->handle($request)
        $menu = Menu::objects()->order(['root', 'lft'])->all();

        return $this->render('admin/menu/menu/list.html', [
            'menu' => $menu,
        ]);
    }

    public function create(Request $request)
    {
        $menu = new Menu();

        $form = $this->createForm(MenuForm::class, $menu, [
            'method' => 'POST',
            'action' => $this->generateUrl('admin_rise_menu_create'),
        ]);

        if ($form->handleRequest($request) && $form->isValid()) {
            $menu = $form->getData();
            if (false === $menu->save()) {
                throw new \RuntimeException('Error while save menu');
            }

            $this->addFlash('success', 'Меню успешно сохранено');

            return $this->redirectToRoute('admin_rise_menu_list');
        }

        return $this->render('admin/menu/menu/create.html', [
            'form' => $form->createView(),
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
            'action' => $this->generateUrl('admin_rise_menu_update', ['id' => $id]),
        ]);

        if ($form->handleRequest($request) && $form->isValid()) {
            $menu = $form->getData();
            if (false === $menu->save()) {
                throw new \RuntimeException('Error while save menu');
            }

            $this->addFlash('success', 'Меню успешно сохранено');

            return $this->redirectToRoute('admin_rise_menu_list');
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

        return $this->redirectToRoute('admin_rise_menu_list');
    }
}
