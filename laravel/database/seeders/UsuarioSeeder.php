<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;

class UsuarioSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if (App::environment() === 'production') {
            DB::unprepared("INSERT INTO [users] (
                [name]
                ,[matricula]
                ,[email]
                ,[cargo]
                ,[funcao]
                ,[fisica]
                ,[unidade]
            )
            SELECT
                RTRIM([no_empregado]) as [name]
                ,RTRIM([nu_matricula]) as [matricula]
                ,NULL as [email]
                ,RTRIM([co_cargo]) as [cargo]
                ,RTRIM([no_funcao]) as [funcao]
                ,RTRIM([co_lot_fisica]) as [fisica]
                ,RTRIM([co_lot_adm]) as [unidade]
            FROM [ATENDIMENTO].[dbo].[RH_EMPREGADOS]
            WHERE [nu_matricula] IN(
                SELECT DISTINCT RTRIM([CO_MATRICULA]) FROM [RH_UNIDADES].[dbo].[EMPREGADOS_SEV]
                UNION
                SELECT DISTINCT RTRIM([CO_COORDENADOR]) FROM [RH_UNIDADES].[dbo].[EMPREGADOS_SEV]
                UNION
                SELECT DISTINCT RTRIM([CO_SUPERVISOR]) FROM [RH_UNIDADES].[dbo].[EMPREGADOS_SEV])
            ;");
        } else {
            DB::unprepared("
            SET IDENTITY_INSERT [dbo].[users] ON
INSERT [dbo].[users] ([id], [name], [matricula], [email], [cargo], [funcao], [fisica], [unidade], [simulando], [remember_token], [created_at], [updated_at]) VALUES (1, N'LUIS CARLOS BIANCHI', N'C028583', NULL, N'TBN', N'ASSISTENTE SENIOR', 6169, 7072, NULL, NULL, NULL, NULL)
INSERT [dbo].[users] ([id], [name], [matricula], [email], [cargo], [funcao], [fisica], [unidade], [simulando], [remember_token], [created_at], [updated_at]) VALUES (2, N'WILSON DIAS BICALHO', N'C049546', NULL, N'TBN', N'ASSISTENTE SENIOR', 7743, 7072, NULL, NULL, NULL, NULL)
INSERT [dbo].[users] ([id], [name], [matricula], [email], [cargo], [funcao], [fisica], [unidade], [simulando], [remember_token], [created_at], [updated_at]) VALUES (3, N'HEBER PAULO PASSOS', N'C023880', NULL, N'TBN', N'ASSISTENTE SENIOR', 7083, 7072, NULL, NULL, NULL, NULL)
INSERT [dbo].[users] ([id], [name], [matricula], [email], [cargo], [funcao], [fisica], [unidade], [simulando], [remember_token], [created_at], [updated_at]) VALUES (4, N'VANESSA SEPTIMIO ALVES GOMES', N'C040445', NULL, N'TBN', N'ASSISTENTE SENIOR', 7074, 7072, NULL, NULL, NULL, NULL)
INSERT [dbo].[users] ([id], [name], [matricula], [email], [cargo], [funcao], [fisica], [unidade], [simulando], [remember_token], [created_at], [updated_at]) VALUES (5, N'FRANCISCA ANTONIA NARCIZO', N'C029188', NULL, N'TBN', N'ASSISTENTE SENIOR', 7932, 7072, NULL, NULL, NULL, NULL)
INSERT [dbo].[users] ([id], [name], [matricula], [email], [cargo], [funcao], [fisica], [unidade], [simulando], [remember_token], [created_at], [updated_at]) VALUES (6, N'GISLENE APARECIDA DIAS MARSICANO', N'C029893', NULL, N'TBN', N'ASSISTENTE SENIOR', 7174, 7072, NULL, NULL, NULL, NULL)
INSERT [dbo].[users] ([id], [name], [matricula], [email], [cargo], [funcao], [fisica], [unidade], [simulando], [remember_token], [created_at], [updated_at]) VALUES (7, N'HELENA MIDORI IDERIHA MARTELLI', N'C037679', NULL, N'TBN', N'CONSULTOR REGIONAL I 6H', 313, 7072, NULL, NULL, NULL, NULL)
INSERT [dbo].[users] ([id], [name], [matricula], [email], [cargo], [funcao], [fisica], [unidade], [simulando], [remember_token], [created_at], [updated_at]) VALUES (8, N'NELSON MOCELIN', N'C041672', NULL, N'TBN', N'CONSULTOR REGIONAL I 6H', 6162, 7072, NULL, NULL, NULL, NULL)
INSERT [dbo].[users] ([id], [name], [matricula], [email], [cargo], [funcao], [fisica], [unidade], [simulando], [remember_token], [created_at], [updated_at]) VALUES (9, N'DANIELA CRISTINA DE RONDAN VIANA', N'C021934', NULL, N'TBN', N'ASSISTENTE SENIOR', 7932, 7072, NULL, NULL, NULL, NULL)
INSERT [dbo].[users] ([id], [name], [matricula], [email], [cargo], [funcao], [fisica], [unidade], [simulando], [remember_token], [created_at], [updated_at]) VALUES (10, N'FABIO BARROS DA FONSECA PEREIRA', N'C066509', NULL, N'TBN', N'CONSULTOR REGIONAL I 8H', 7932, 7072, NULL, NULL, NULL, NULL)
INSERT [dbo].[users] ([id], [name], [matricula], [email], [cargo], [funcao], [fisica], [unidade], [simulando], [remember_token], [created_at], [updated_at]) VALUES (11, N'RONY RODRIGUES RAMOS', N'C059051', NULL, N'TBN', N'ASSISTENTE SENIOR', 2627, 7072, NULL, NULL, NULL, NULL)
INSERT [dbo].[users] ([id], [name], [matricula], [email], [cargo], [funcao], [fisica], [unidade], [simulando], [remember_token], [created_at], [updated_at]) VALUES (12, N'MOZART NOGAROLLI', N'C059437', NULL, N'TBN', N'ASSISTENTE SENIOR', 7083, 7072, NULL, NULL, NULL, NULL)
INSERT [dbo].[users] ([id], [name], [matricula], [email], [cargo], [funcao], [fisica], [unidade], [simulando], [remember_token], [created_at], [updated_at]) VALUES (13, N'THAIS BARBOSA HENDLER', N'C070903', NULL, N'TBN', N'SUPERVISOR CENTR FILIAL', 7097, 7072, NULL, NULL, NULL, NULL)
INSERT [dbo].[users] ([id], [name], [matricula], [email], [cargo], [funcao], [fisica], [unidade], [simulando], [remember_token], [created_at], [updated_at]) VALUES (14, N'ADENILSON OLIVEIRA SANTOS', N'C067216', NULL, N'TBN', N'ASSISTENTE SENIOR', 6147, 7072, NULL, NULL, NULL, NULL)
INSERT [dbo].[users] ([id], [name], [matricula], [email], [cargo], [funcao], [fisica], [unidade], [simulando], [remember_token], [created_at], [updated_at]) VALUES (15, N'JOSIANE RECIOLINO DE OLIVEIRA', N'C045238', NULL, N'TBN', N'SUPERVISOR CENTR FILIAL', 7174, 7072, NULL, NULL, NULL, NULL)
INSERT [dbo].[users] ([id], [name], [matricula], [email], [cargo], [funcao], [fisica], [unidade], [simulando], [remember_token], [created_at], [updated_at]) VALUES (16, N'LEANDRO CORAGEM ALVES FERNANDES DA SILVA', N'C078070', NULL, N'TBN', N'ASSISTENTE SENIOR', 7097, 7072, NULL, NULL, NULL, NULL)
INSERT [dbo].[users] ([id], [name], [matricula], [email], [cargo], [funcao], [fisica], [unidade], [simulando], [remember_token], [created_at], [updated_at]) VALUES (17, N'SABRINA LILIAN BELUSSO', N'C074398', NULL, N'TBN', N'ASSISTENTE SENIOR', 6174, 7072, NULL, NULL, NULL, NULL)
INSERT [dbo].[users] ([id], [name], [matricula], [email], [cargo], [funcao], [fisica], [unidade], [simulando], [remember_token], [created_at], [updated_at]) VALUES (18, N'KARLA VITORIA DE SIQUEIRA ALMEIDA', N'C072085', NULL, N'TBN', N'ASSISTENTE SENIOR', 7253, 7072, NULL, NULL, NULL, NULL)
INSERT [dbo].[users] ([id], [name], [matricula], [email], [cargo], [funcao], [fisica], [unidade], [simulando], [remember_token], [created_at], [updated_at]) VALUES (19, N'MARIA CRISTINA DA COSTA CRUZ BITTENCOURT', N'C072127', NULL, N'TBN', N'ASSISTENTE SENIOR', 7083, 7072, NULL, NULL, NULL, NULL)
INSERT [dbo].[users] ([id], [name], [matricula], [email], [cargo], [funcao], [fisica], [unidade], [simulando], [remember_token], [created_at], [updated_at]) VALUES (20, N'GIACOMO PAOLO CANTANHEDE BORRALHO', N'C089196', NULL, N'TBN', N'ASSISTENTE SENIOR', 7074, 7072, NULL, NULL, NULL, NULL)
INSERT [dbo].[users] ([id], [name], [matricula], [email], [cargo], [funcao], [fisica], [unidade], [simulando], [remember_token], [created_at], [updated_at]) VALUES (21, N'SAMUEL CIZEWSKI DUCIONI', N'C054593', NULL, N'TBN', N'ASSISTENTE SENIOR', 6163, 7072, NULL, NULL, NULL, NULL)
INSERT [dbo].[users] ([id], [name], [matricula], [email], [cargo], [funcao], [fisica], [unidade], [simulando], [remember_token], [created_at], [updated_at]) VALUES (22, N'CARLOS EDUARDO RAMOS QUEIROZ', N'C078767', NULL, N'TBN', N'ASSISTENTE SENIOR', 7743, 7072, NULL, NULL, NULL, NULL)
INSERT [dbo].[users] ([id], [name], [matricula], [email], [cargo], [funcao], [fisica], [unidade], [simulando], [remember_token], [created_at], [updated_at]) VALUES (23, N'NARA RAQUEL FIUZA', N'C074846', NULL, N'TBN', N'ASSISTENTE SENIOR', 7174, 7072, NULL, NULL, NULL, NULL)
INSERT [dbo].[users] ([id], [name], [matricula], [email], [cargo], [funcao], [fisica], [unidade], [simulando], [remember_token], [created_at], [updated_at]) VALUES (24, N'SELMA SALOMAO JEZZINI', N'C089330', NULL, N'TBN', N'ASSISTENTE SENIOR', 7072, 7072, NULL, NULL, NULL, NULL)
INSERT [dbo].[users] ([id], [name], [matricula], [email], [cargo], [funcao], [fisica], [unidade], [simulando], [remember_token], [created_at], [updated_at]) VALUES (25, N'ALINE DUMIT LEAL', N'C083642', NULL, N'TBN', N'ASSISTENTE SENIOR', 7074, 7072, NULL, NULL, NULL, NULL)
INSERT [dbo].[users] ([id], [name], [matricula], [email], [cargo], [funcao], [fisica], [unidade], [simulando], [remember_token], [created_at], [updated_at]) VALUES (26, N'DARCI SIMPLICIO CAVALCANTI', N'C075743', NULL, N'TBN', N'ASSISTENTE SENIOR', 6165, 7072, NULL, NULL, NULL, NULL)
INSERT [dbo].[users] ([id], [name], [matricula], [email], [cargo], [funcao], [fisica], [unidade], [simulando], [remember_token], [created_at], [updated_at]) VALUES (27, N'MARIO JORGE RODRIGUES BARROSO JUNIOR', N'C080496', NULL, N'TBN', N'ASSISTENTE SENIOR', 3226, 7072, NULL, NULL, NULL, NULL)
INSERT [dbo].[users] ([id], [name], [matricula], [email], [cargo], [funcao], [fisica], [unidade], [simulando], [remember_token], [created_at], [updated_at]) VALUES (28, N'FILIPE NATHAN ASSUNCAO SABINO', N'C080702', NULL, N'TBN', N'SUPERVISOR CENTR FILIAL', 7713, 7072, NULL, NULL, NULL, NULL)
INSERT [dbo].[users] ([id], [name], [matricula], [email], [cargo], [funcao], [fisica], [unidade], [simulando], [remember_token], [created_at], [updated_at]) VALUES (29, N'DANILO SANTAMARINA DA SILVA', N'C080730', NULL, N'TBN', N'ASSISTENTE SENIOR', 7812, 7072, NULL, NULL, NULL, NULL)
INSERT [dbo].[users] ([id], [name], [matricula], [email], [cargo], [funcao], [fisica], [unidade], [simulando], [remember_token], [created_at], [updated_at]) VALUES (30, N'PEDRO SOLDI HARDT', N'C089888', NULL, N'TBN', N'ASSISTENTE SENIOR', 7717, 7072, NULL, NULL, NULL, NULL)
INSERT [dbo].[users] ([id], [name], [matricula], [email], [cargo], [funcao], [fisica], [unidade], [simulando], [remember_token], [created_at], [updated_at]) VALUES (31, N'LUIZ CLAUDIO SANTOS RIBEIRO', N'C073014', NULL, N'TBN', N'CONSULTOR REGIONAL I 8H', 7771, 7072, NULL, NULL, NULL, NULL)
INSERT [dbo].[users] ([id], [name], [matricula], [email], [cargo], [funcao], [fisica], [unidade], [simulando], [remember_token], [created_at], [updated_at]) VALUES (32, N'LEONARDO JOSE MARTINS CARNEIRO', N'C073029', NULL, N'TBN', N'COORDENADOR CENTR FILIAL', 7072, 7072, NULL, NULL, NULL, NULL)
INSERT [dbo].[users] ([id], [name], [matricula], [email], [cargo], [funcao], [fisica], [unidade], [simulando], [remember_token], [created_at], [updated_at]) VALUES (33, N'MARCUS RODRIGO DE LIMA LOPES', N'C073128', NULL, N'TBN', N'ASSISTENTE SENIOR', 7174, 7072, NULL, NULL, NULL, NULL)
INSERT [dbo].[users] ([id], [name], [matricula], [email], [cargo], [funcao], [fisica], [unidade], [simulando], [remember_token], [created_at], [updated_at]) VALUES (34, N'LUIS FELIPE DE OLIVEIRA BRASCO', N'C092364', NULL, N'TBN', N'SUPERVISOR CENTR FILIAL', 7072, 7072, NULL, NULL, NULL, NULL)
INSERT [dbo].[users] ([id], [name], [matricula], [email], [cargo], [funcao], [fisica], [unidade], [simulando], [remember_token], [created_at], [updated_at]) VALUES (35, N'ROGERIO DE SERVI FERRAZ', N'C087978', NULL, N'TBN', N'ASSISTENTE SENIOR', 6173, 7072, NULL, NULL, NULL, NULL)
INSERT [dbo].[users] ([id], [name], [matricula], [email], [cargo], [funcao], [fisica], [unidade], [simulando], [remember_token], [created_at], [updated_at]) VALUES (36, N'ALEXANDRE DE ARAUJO BORGES', N'C068966', NULL, N'TBN', N'ASSISTENTE SENIOR', 6176, 7072, NULL, NULL, NULL, NULL)
INSERT [dbo].[users] ([id], [name], [matricula], [email], [cargo], [funcao], [fisica], [unidade], [simulando], [remember_token], [created_at], [updated_at]) VALUES (37, N'ANA LUIZA FREIRE DE JESUS', N'C077863', NULL, N'TBN', N'SUPERVISOR CENTR FILIAL', 7777, 7072, NULL, NULL, NULL, NULL)
INSERT [dbo].[users] ([id], [name], [matricula], [email], [cargo], [funcao], [fisica], [unidade], [simulando], [remember_token], [created_at], [updated_at]) VALUES (38, N'SANDRO DE OLIVEIRA SOARES', N'C092565', NULL, N'TBN', N'ASSISTENTE SENIOR', 7097, 7072, NULL, NULL, NULL, NULL)
INSERT [dbo].[users] ([id], [name], [matricula], [email], [cargo], [funcao], [fisica], [unidade], [simulando], [remember_token], [created_at], [updated_at]) VALUES (39, N'CHERYL CHIARA CASTELLI SOUZA SCHROEDER', N'C092678', NULL, N'ARQT8H', N'SUPERVISOR CENTR FILIAL', 7083, 7072, NULL, NULL, NULL, NULL)
INSERT [dbo].[users] ([id], [name], [matricula], [email], [cargo], [funcao], [fisica], [unidade], [simulando], [remember_token], [created_at], [updated_at]) VALUES (40, N'GIOVANI LUCIO MOCELLIN', N'C088372', NULL, N'TBN', N'COORDENADOR CENTR FILIAL', 6164, 7072, NULL, NULL, NULL, NULL)
INSERT [dbo].[users] ([id], [name], [matricula], [email], [cargo], [funcao], [fisica], [unidade], [simulando], [remember_token], [created_at], [updated_at]) VALUES (41, N'CAMILA JULIANA BATISTA NAKASATO', N'C096098', NULL, N'TBN', N'ASSISTENTE SENIOR', 7829, 7072, NULL, NULL, NULL, NULL)
INSERT [dbo].[users] ([id], [name], [matricula], [email], [cargo], [funcao], [fisica], [unidade], [simulando], [remember_token], [created_at], [updated_at]) VALUES (42, N'JANAINA DE SOUSA SILVA', N'C086622', NULL, N'TBN', N'ASSISTENTE SENIOR', 7097, 7072, NULL, NULL, NULL, NULL)
INSERT [dbo].[users] ([id], [name], [matricula], [email], [cargo], [funcao], [fisica], [unidade], [simulando], [remember_token], [created_at], [updated_at]) VALUES (43, N'FABIO PROCESI COUTINHO', N'C088700', NULL, N'TBN', N'ASSISTENTE SENIOR', 7011, 7072, NULL, NULL, NULL, NULL)
INSERT [dbo].[users] ([id], [name], [matricula], [email], [cargo], [funcao], [fisica], [unidade], [simulando], [remember_token], [created_at], [updated_at]) VALUES (44, N'KLEBER JOAO THEODORO GUEDES DE CARVALHO', N'C088941', NULL, N'TBN', N'ASSISTENTE SENIOR', 7777, 7072, NULL, NULL, NULL, NULL)
INSERT [dbo].[users] ([id], [name], [matricula], [email], [cargo], [funcao], [fisica], [unidade], [simulando], [remember_token], [created_at], [updated_at]) VALUES (45, N'FLAVIA MARIA ASSIS SOUZA MENDES', N'C091694', NULL, N'TBN', N'ASSISTENTE SENIOR', 7740, 7072, NULL, NULL, NULL, NULL)
INSERT [dbo].[users] ([id], [name], [matricula], [email], [cargo], [funcao], [fisica], [unidade], [simulando], [remember_token], [created_at], [updated_at]) VALUES (46, N'THAIS AGHAT MAGALHAES ORESTES', N'C091844', NULL, N'TBN', N'ASSISTENTE SENIOR', 7712, 7072, NULL, NULL, NULL, NULL)
INSERT [dbo].[users] ([id], [name], [matricula], [email], [cargo], [funcao], [fisica], [unidade], [simulando], [remember_token], [created_at], [updated_at]) VALUES (47, N'PAULO FERNANDO PERDOMO', N'C086816', NULL, N'TBN', N'ASSISTENTE DE VAREJO', 5668, 5668, NULL, NULL, NULL, NULL)
INSERT [dbo].[users] ([id], [name], [matricula], [email], [cargo], [funcao], [fisica], [unidade], [simulando], [remember_token], [created_at], [updated_at]) VALUES (48, N'CAROLINA ROSA DINIZ LIMA SOTERO', N'C087205', NULL, N'TBN', NULL, 7466, 7072, NULL, NULL, NULL, NULL)
INSERT [dbo].[users] ([id], [name], [matricula], [email], [cargo], [funcao], [fisica], [unidade], [simulando], [remember_token], [created_at], [updated_at]) VALUES (49, N'EDER ANTONIO COELHO', N'C100269', NULL, N'TBN', N'ASSISTENTE SENIOR', 6164, 7072, NULL, NULL, NULL, NULL)
INSERT [dbo].[users] ([id], [name], [matricula], [email], [cargo], [funcao], [fisica], [unidade], [simulando], [remember_token], [created_at], [updated_at]) VALUES (50, N'MARIANA CARDOSO NOVA', N'C100421', NULL, N'TBN', N'ASSISTENTE SENIOR', 7757, 7072, NULL, NULL, NULL, NULL)
INSERT [dbo].[users] ([id], [name], [matricula], [email], [cargo], [funcao], [fisica], [unidade], [simulando], [remember_token], [created_at], [updated_at]) VALUES (51, N'PAULO CESAR MINEIRO LOPES', N'C081711', NULL, N'TBN', N'ASSISTENTE SENIOR', 7074, 7072, NULL, NULL, NULL, NULL)
INSERT [dbo].[users] ([id], [name], [matricula], [email], [cargo], [funcao], [fisica], [unidade], [simulando], [remember_token], [created_at], [updated_at]) VALUES (52, N'ULLISSES CASTRO PALMEIRA', N'C102612', NULL, N'TBN', N'ASSISTENTE SENIOR', 2628, 7072, NULL, NULL, NULL, NULL)
INSERT [dbo].[users] ([id], [name], [matricula], [email], [cargo], [funcao], [fisica], [unidade], [simulando], [remember_token], [created_at], [updated_at]) VALUES (53, N'MARCIO DIEGO DE OLIVEIRA CARVALHO', N'C100777', NULL, N'TBN', N'ASSISTENTE SENIOR', 7775, 7072, NULL, NULL, NULL, NULL)
INSERT [dbo].[users] ([id], [name], [matricula], [email], [cargo], [funcao], [fisica], [unidade], [simulando], [remember_token], [created_at], [updated_at]) VALUES (54, N'HUGO DE SOUZA LEAO', N'C112153', NULL, N'ARQT8H', N'SUPERVISOR CENTR FILIAL', 7740, 7072, NULL, NULL, NULL, NULL)
INSERT [dbo].[users] ([id], [name], [matricula], [email], [cargo], [funcao], [fisica], [unidade], [simulando], [remember_token], [created_at], [updated_at]) VALUES (55, N'MARCIA MOTTA RAMALHO', N'C122996', NULL, N'TBN', N'ASSISTENTE SENIOR', 7074, 7072, NULL, NULL, NULL, NULL)
INSERT [dbo].[users] ([id], [name], [matricula], [email], [cargo], [funcao], [fisica], [unidade], [simulando], [remember_token], [created_at], [updated_at]) VALUES (56, N'JOSE EDI MORAES FAGUNDES JUNIOR', N'C082581', NULL, N'TBN', N'ASSISTENTE SENIOR', 2636, 7072, NULL, NULL, NULL, NULL)
INSERT [dbo].[users] ([id], [name], [matricula], [email], [cargo], [funcao], [fisica], [unidade], [simulando], [remember_token], [created_at], [updated_at]) VALUES (57, N'CECILIA JUNQUEIRA TOLOMEI SEABRA', N'C082660', NULL, N'TBN', N'ASSISTENTE SENIOR', 7074, 7072, NULL, NULL, NULL, NULL)
INSERT [dbo].[users] ([id], [name], [matricula], [email], [cargo], [funcao], [fisica], [unidade], [simulando], [remember_token], [created_at], [updated_at]) VALUES (58, N'ALCINEIDE DE LIMA SILVA', N'C093841', NULL, N'TBN', N'ASSISTENTE SENIOR', 5813, 7072, NULL, NULL, NULL, NULL)
INSERT [dbo].[users] ([id], [name], [matricula], [email], [cargo], [funcao], [fisica], [unidade], [simulando], [remember_token], [created_at], [updated_at]) VALUES (59, N'GIOVANA DE ANDRADE CAVALCANTI HOLANDA', N'C099562', NULL, N'TBN', N'ASSISTENTE SENIOR', 7253, 7072, NULL, NULL, NULL, NULL)
INSERT [dbo].[users] ([id], [name], [matricula], [email], [cargo], [funcao], [fisica], [unidade], [simulando], [remember_token], [created_at], [updated_at]) VALUES (60, N'LEONARDO PONTE DE MENESES', N'C099819', NULL, N'ENGET8', N'SUPERVISOR CENTR FILIAL', 7011, 7072, NULL, NULL, NULL, NULL)
INSERT [dbo].[users] ([id], [name], [matricula], [email], [cargo], [funcao], [fisica], [unidade], [simulando], [remember_token], [created_at], [updated_at]) VALUES (61, N'PRISCILA GABARDO KLEIN', N'C098236', NULL, N'TBN', N'ASSESSOR EXECUTIVO', 5173, 5173, NULL, NULL, NULL, NULL)
INSERT [dbo].[users] ([id], [name], [matricula], [email], [cargo], [funcao], [fisica], [unidade], [simulando], [remember_token], [created_at], [updated_at]) VALUES (62, N'ANDRE LUIZ ARCELA DE MIRANDA FEITOZA', N'C123708', NULL, N'TBN', N'ASSISTENTE SENIOR', 7777, 7072, NULL, NULL, NULL, NULL)
INSERT [dbo].[users] ([id], [name], [matricula], [email], [cargo], [funcao], [fisica], [unidade], [simulando], [remember_token], [created_at], [updated_at]) VALUES (63, N'HELYSON SOARES DE SOUZA MAGALHAES', N'C123767', NULL, N'TBN', N'ASSISTENTE SENIOR', 3227, 7072, NULL, NULL, NULL, NULL)
INSERT [dbo].[users] ([id], [name], [matricula], [email], [cargo], [funcao], [fisica], [unidade], [simulando], [remember_token], [created_at], [updated_at]) VALUES (64, N'ELAINE GONCALVES ANDRADE', N'C123904', NULL, N'TBN', N'ASSISTENTE SENIOR', 5278, 7072, NULL, NULL, NULL, NULL)
INSERT [dbo].[users] ([id], [name], [matricula], [email], [cargo], [funcao], [fisica], [unidade], [simulando], [remember_token], [created_at], [updated_at]) VALUES (65, N'LIGIA MARIA MENDES DA SILVA REGO', N'C107193', NULL, N'TBN', N'ASSISTENTE SENIOR', 2656, 7072, NULL, NULL, NULL, NULL)
INSERT [dbo].[users] ([id], [name], [matricula], [email], [cargo], [funcao], [fisica], [unidade], [simulando], [remember_token], [created_at], [updated_at]) VALUES (66, N'ANDERSON CEZAR SABADIN', N'C094828', NULL, N'ENGET8', N'COORDENADOR CENTR FILIAL', 7743, 7072, NULL, NULL, NULL, NULL)
INSERT [dbo].[users] ([id], [name], [matricula], [email], [cargo], [funcao], [fisica], [unidade], [simulando], [remember_token], [created_at], [updated_at]) VALUES (67, N'ALINE PONTES DE OLIVEIRA', N'C110818', NULL, N'TBN', N'ASSISTENTE SENIOR', 7074, 7072, NULL, NULL, NULL, NULL)
INSERT [dbo].[users] ([id], [name], [matricula], [email], [cargo], [funcao], [fisica], [unidade], [simulando], [remember_token], [created_at], [updated_at]) VALUES (68, N'CASSIO RONIER RODRIGUES CARDOSO E SILVA', N'C118019', NULL, N'TBN', N'ASSISTENTE SENIOR', 2690, 7072, NULL, NULL, NULL, NULL)
INSERT [dbo].[users] ([id], [name], [matricula], [email], [cargo], [funcao], [fisica], [unidade], [simulando], [remember_token], [created_at], [updated_at]) VALUES (69, N'ANDRESSA RODRIGUES BARCELLOS DE LIMA', N'C135824', NULL, N'TBN', N'ASSISTENTE SENIOR', 7074, 7072, NULL, NULL, NULL, NULL)
INSERT [dbo].[users] ([id], [name], [matricula], [email], [cargo], [funcao], [fisica], [unidade], [simulando], [remember_token], [created_at], [updated_at]) VALUES (70, N'TEREZINHA CAMPAGNOLO', N'C868572', NULL, N'TBN', N'CONSULTOR REGIONAL I 6H', 7072, 7072, NULL, NULL, NULL, NULL)
INSERT [dbo].[users] ([id], [name], [matricula], [email], [cargo], [funcao], [fisica], [unidade], [simulando], [remember_token], [created_at], [updated_at]) VALUES (71, N'CAROLINA DIAS VILANOVA', N'C118843', NULL, N'TBN', N'ASSISTENTE SENIOR', 6170, 7072, NULL, NULL, NULL, NULL)
INSERT [dbo].[users] ([id], [name], [matricula], [email], [cargo], [funcao], [fisica], [unidade], [simulando], [remember_token], [created_at], [updated_at]) VALUES (72, N'MANOEL FERREIRA DE ALMEIDA NETO', N'C132747', NULL, N'ENGET8', N'COORDENADOR CENTR FILIAL', 7743, 7072, NULL, NULL, NULL, NULL)
INSERT [dbo].[users] ([id], [name], [matricula], [email], [cargo], [funcao], [fisica], [unidade], [simulando], [remember_token], [created_at], [updated_at]) VALUES (73, N'PEDRO MACHADO PESTKA', N'C129638', NULL, N'TBN', N'ASSISTENTE SENIOR', 6161, 7072, NULL, NULL, NULL, NULL)
INSERT [dbo].[users] ([id], [name], [matricula], [email], [cargo], [funcao], [fisica], [unidade], [simulando], [remember_token], [created_at], [updated_at]) VALUES (74, N'VITOR MOREIRA ANGELIM PESSOA', N'C131443', NULL, N'TBN', N'ASSISTENTE SENIOR', 7011, 7072, NULL, NULL, NULL, NULL)
INSERT [dbo].[users] ([id], [name], [matricula], [email], [cargo], [funcao], [fisica], [unidade], [simulando], [remember_token], [created_at], [updated_at]) VALUES (75, N'MARINA GERASSO', N'C131848', NULL, N'TBN', N'ASSISTENTE SENIOR', 7074, 7072, NULL, NULL, NULL, NULL)
INSERT [dbo].[users] ([id], [name], [matricula], [email], [cargo], [funcao], [fisica], [unidade], [simulando], [remember_token], [created_at], [updated_at]) VALUES (76, N'RAUL JOSE SILVA PONTES', N'C137247', NULL, N'TBN', N'ASSISTENTE SENIOR', 7635, 7635, NULL, NULL, NULL, NULL)
INSERT [dbo].[users] ([id], [name], [matricula], [email], [cargo], [funcao], [fisica], [unidade], [simulando], [remember_token], [created_at], [updated_at]) VALUES (77, N'ALESSANDRO RAGGIO GUAPYASSU LINS', N'C133725', NULL, N'TBN', N'ASSISTENTE SENIOR', 7074, 7072, NULL, NULL, NULL, NULL)
INSERT [dbo].[users] ([id], [name], [matricula], [email], [cargo], [funcao], [fisica], [unidade], [simulando], [remember_token], [created_at], [updated_at]) VALUES (78, N'SABRINA GRACILIANO DE ANDRADE', N'C127136', NULL, N'TBN', N'ASSISTENTE SENIOR', 7074, 7072, NULL, NULL, NULL, NULL)
INSERT [dbo].[users] ([id], [name], [matricula], [email], [cargo], [funcao], [fisica], [unidade], [simulando], [remember_token], [created_at], [updated_at]) VALUES (79, N'FABIANA OLIVEIRA DOS SANTOS', N'C137793', NULL, N'TBN', N'ASSISTENTE SENIOR', 7097, 7072, NULL, NULL, NULL, NULL)
INSERT [dbo].[users] ([id], [name], [matricula], [email], [cargo], [funcao], [fisica], [unidade], [simulando], [remember_token], [created_at], [updated_at]) VALUES (80, N'ADRIELLE DE JESUS SILVA NASCIMENTO', N'C137995', NULL, N'TBN', N'ASSISTENTE SENIOR', 7777, 7072, NULL, NULL, NULL, NULL)
INSERT [dbo].[users] ([id], [name], [matricula], [email], [cargo], [funcao], [fisica], [unidade], [simulando], [remember_token], [created_at], [updated_at]) VALUES (81, N'ROSIMEIRE GARCIA BADIN', N'C128255', NULL, N'TBN', N'ASSISTENTE SENIOR', 7097, 7072, NULL, NULL, NULL, NULL)
INSERT [dbo].[users] ([id], [name], [matricula], [email], [cargo], [funcao], [fisica], [unidade], [simulando], [remember_token], [created_at], [updated_at]) VALUES (82, N'JOAO BAPTISTA DE LIMA JUNIOR', N'C378200', NULL, N'TBN', N'CONSULTOR REGIONAL I 6H', 7733, 7072, NULL, NULL, NULL, NULL)
INSERT [dbo].[users] ([id], [name], [matricula], [email], [cargo], [funcao], [fisica], [unidade], [simulando], [remember_token], [created_at], [updated_at]) VALUES (83, N'RAIMUNDO EVERTON DE AQUINO MOREIRA', N'C082807', N'RAIMUNDO.A.MOREIRA@CAIXA.GOV.BR', N'TECNICO BANCARIO NOVO', N'GERENTE EXECUTIVO', 5531, 5531, NULL, NULL, CAST(N'2021-06-30T12:52:17.177' AS DateTime), CAST(N'2021-07-01T12:10:20.110' AS DateTime))
SET IDENTITY_INSERT [dbo].[users] OFF");
        }
    }
}
