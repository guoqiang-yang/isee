/*-----------------------------------------------
 * 管理后台账号表
 *-----------------------------------------------*/
CREATE TABLE t_staff_user (
  suid              int             not null auto_increment,
  name              varchar(64)     not null default ''     comment '名称',
  mobile            varchar(11)     not null default ''     comment '手机号',
  password          varchar(64)     not null default ''     comment '密码',
  salt              char(4)         not null default ''     comment '掩码',
  status            tinyint         not null default 0      comment '0-正常 1-删除',
  ctime             timestamp       not null default 0,
  mtime             timestamp       not null default current_timestamp ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (suid),
  KEY ix_mobile (mobile)
) ENGINE=InnoDB AUTO_INCREMENT=1000 DEFAULT CHARSET=utf8mb4 COMMENT='管理后台账号';

/*-----------------------------------------------
 * 管理后台权限表
 *-----------------------------------------------*/
CREATE TABLE t_staff_role (
  role_id           int             not null auto_increment,
  ch_name           varchar(64)     not null default ''     comment '权限名称（中）',
  en_name           varchar(128)    not null default ''     comment '权限名称（英）',
  status            tinyint         not null default 0      comment '0-正常 1-删除',
  ctime             timestamp       not null default 0,
  mtime             timestamp       not null default current_timestamp ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (role_id)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COMMENT='管理后台权限表';

/*-----------------------------------------------
 * 管理后台权限组表
 *-----------------------------------------------*/
CREATE TABLE t_staff_role_group (
  id                int             not null auto_increment,
  group_id          int             not null default 0      comment '权限组id',
  role_ids          varchar(256)    not null default ''     comment '权限ids，分隔符(;)',
  status            tinyint         not null default 0      comment '0-正常 1-删除',
  ctime             timestamp       not null default 0,
  mtime             timestamp       not null default current_timestamp ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  KEY ix_group_id (group_id)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COMMENT='管理后台权限组表';

/*-----------------------------------------------
 * 权限关系表
 *-----------------------------------------------*/
CREATE TABLE t_staff_group_relation (
  id                int             not null auto_increment,
  suid              int             not null default 0      comment '用户id',
  group_ids         varchar(64)     not null default ''     comment '权限组ids',
  status            tinyint         not null default 0      comment '0-正常 1-删除',
  ctime             timestamp       not null default 0,
  mtime             timestamp       not null default current_timestamp ON UPDATE CURRENT_TIMESTAMP,

  PRIMARY KEY (id),
  KEY ix_suid (suid)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COMMENT='权限关系表';

/*-----------------------------------------------
 * 管理后台修改日志表
 *-----------------------------------------------*/
CREATE TABLE t_staff_modify_log (
  id                int             not null auto_increment,
  suid              int             not null default 0        comment '修改者id',
  name              varchar(8)      not null default ''       comment '修改者名称',
  type              varchar(16)     not null default ''       comment '修改对象：user, role, group, relation ...',
  old_val           varchar(256)    not null default ''       comment '原值',
  new_val           varchar(256)    not null default ''       comment '新值',
  ctime             timestamp       not null default 0,
  mtime             timestamp       not null default current_timestamp ON UPDATE CURRENT_TIMESTAMP,
  PRIMARRY KEY (id),
  KEY ix_suid (suid)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COMMENT='管理后台修改日志表';