--
-- Insert de usuario administrador en tabla `usuario`
--
INSERT INTO `usuario` VALUES ('1', '0000000000', 'Cristian', 'Paolini',
    '0000000000', 'Dirección 3550', 'administrador@gmail.com', 'Administrador', 'WVBHWHJ1WTIvVktma0NqaUpEVzNSQT09',
    'Activa', '1');



-- NOTA: La clave es "administrador", al igual que el nombre de usuario. Pero todos los datos
-- del usuario principal podrán ser modificados una vez se encuentre logueado en el sistema,
-- excepto el ID.