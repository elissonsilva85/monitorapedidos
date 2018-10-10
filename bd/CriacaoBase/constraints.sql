--
-- Limitadores para a tabela `companies`
--
ALTER TABLE `companies`
  ADD CONSTRAINT `fk_company_x_user_approved` FOREIGN KEY (`approved_by`) REFERENCES `users` (`login`),
  ADD CONSTRAINT `fk_company_x_user_created` FOREIGN KEY (`created_by`) REFERENCES `users` (`login`);

--
-- Limitadores para a tabela `mail_queue`
--
ALTER TABLE `mail_queue`
  ADD CONSTRAINT `fk_mail_x_tracking` FOREIGN KEY (`tracking_id`) REFERENCES `trackings` (`tracking_id`);

--
-- Limitadores para a tabela `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `fk_order_x_seller` FOREIGN KEY (`seller_id`) REFERENCES `sellers` (`seller_id`),
  ADD CONSTRAINT `fk_order_x_shop` FOREIGN KEY (`shop_id`) REFERENCES `shops` (`shop_id`),
  ADD CONSTRAINT `fk_order_x_user` FOREIGN KEY (`created_by`) REFERENCES `users` (`login`);

--
-- Limitadores para a tabela `sellers`
--
ALTER TABLE `sellers`
  ADD CONSTRAINT `fk_seller_x_shop` FOREIGN KEY (`shop_id`) REFERENCES `shops` (`shop_id`),
  ADD CONSTRAINT `fk_seller_x_user` FOREIGN KEY (`created_by`) REFERENCES `users` (`login`);

--
-- Limitadores para a tabela `shops`
--
ALTER TABLE `shops`
  ADD CONSTRAINT `fk_shop_x_user_aprooved` FOREIGN KEY (`approved_by`) REFERENCES `users` (`login`),
  ADD CONSTRAINT `fk_shop_x_user_created` FOREIGN KEY (`created_by`) REFERENCES `users` (`login`);

--
-- Limitadores para a tabela `trackings`
--
ALTER TABLE `trackings`
  ADD CONSTRAINT `fk_tracking_x_company` FOREIGN KEY (`company_id`) REFERENCES `companies` (`company_id`),
  ADD CONSTRAINT `fk_tracking_x_order` FOREIGN KEY (`order_id`) REFERENCES `orders` (`order_id`),
  ADD CONSTRAINT `fk_tracking_x_user` FOREIGN KEY (`created_by`) REFERENCES `users` (`login`);
